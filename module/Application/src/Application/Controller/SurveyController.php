<?php
namespace Application\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;
use \Light\Mvc\Middleware\SessionCookie as SessionCookie;

class SurveyController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Application';
        parent::__construct();

        $this->_connectDb();
        session_start();
    }

    public function listinfo()
    {
        $page = isset($_GET['page']) ? $_GEt['page'] : 1;
        $ip = isset($_GET['ip']) ? $_GET['ip'] : '';
        $userAgent = isset($_GET['user_agent']) ? $_GET['user_agent'] : '';
        $where = ' 1 ';
        $where .= empty($ip) ? '' : "AND `ip` = {$ip}";
        $where .= empty($userAgent) ? '' : "AND `user_agent` = {$userAgent}";

        $getNum = mysql_query("SELECT COUNT(*) AS `nums` FROM `workstudio_test`.`wt_survey` WHERE {$where}");
		$nums = mysql_fetch_row($getNum);
        $nums = $nums[0];

        $start = ($page - 1) * 20;
        $getInfos = mysql_query("SELECT `id`, `age`, `job_address`, `mapapp`, `create_time`, `ip`, `user_agent` FROM `workstudio_test`.`wt_survey` WHERE {$where} ORDER BY `create_time` DESC LIMIT {$start}, 20");
        $infos = array();
        while ($row = mysql_fetch_assoc($getInfos)) {
            $row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
            $infos[] = $row;
        }
        $data = array(
            'application' => $this->application,
            'infos' => $infos,
            'nums' => $nums,
        );

        $this->application->layout('listinfo', 'common/layout', $data);
        
    }

    public function show()
    {
        $questions = $this->_getQuestions();
        $id = isset($_GET['id']) ? $_GET['id'] : false;
        if (empty($id)) {
            exit('param error');
        }
        $getInfo = mysql_query("SELECT * FROM `workstudio_test`.`wt_survey` WHERE `id` = {$id}");
		$info = mysql_fetch_assoc($getInfo);

        $data = array(
            'questions' => $questions,
            'application' => $this->application,
            'values' => $info,
            'isShow' => true,
        );
        $this->application->render('survey', $data);
    }

    public function index()
    {
        $questions = $this->_getQuestions();
        $values = $_GET;
        $sign = $this->_getRandomStr();
        $_SESSION['sign'] = $sign;
        $data = array(
            'sign' => $sign,
            'application' => $this->application,
            'questions' => $questions,
            'values' => $values
        );
        $this->application->render('survey', $data);
    }

    public function answer()
    {
        $infos = $_GET;
        $mySign = isset($_SESSION['sign']) ? $_SESSION['sign'] : '';
        unset($_SESSION['sign']);
        $sign = isset($infos['sign']) ? $infos['sign'] : '';
        if (empty($mySign) || empty($sign) || $mySign != $sign) {
            exit('error');
        }
        $questions = $this->_getQuestions();
        $data = array_keys($questions['baseQuestions']);
        foreach ($questions['questions'] as $code => $info) {
            $data[] = $code . '_have';
            $data[] = $code . '_no';
        }

        $sql = "`create_time` = {$this->time}, `ip` = '{$this->ip}',";
        foreach ($data as $key) {
            $value = isset($infos[$key]) ? $infos[$key] : false;
            if (empty($value)) {
                exit('param error ' . $key);
            }
            $sql .= "`{$key}` = '{$value}',";
        }

        $data['ip'] = $this->_getIp();
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $sql .= "`user_agent` = '{$userAgent}'";
        $insertSql = "INSERT INTO `workstudio_test`.`wt_survey` SET {$sql}";
        $result = @mysql_query($insertSql);
        var_dump($result);
        exit();
    }

    private function _getQuestions()
    {
        $baseQuestions = array(
            'age' => array('title' => '年龄'),
            'jobs_address' => array('title' => '工作地点'),
            'career' => array('title' => '职业'),
            'mapapp' => array('title' => '目前使用的地图APP'),
        );

        $questions = array(
            'q1' => array(
                'title' => '一键定位',
                'description' => '点击地图APP图标，软件开始运行后自动的以一个小圆点或者箭头来标记用户在地图上的位置（类似谷歌地图的定位功能），或者软件开始运行后，用户通过一键点击一个按钮来要求软件在地图上标示用户的当前位置。',
            ),
            'q2' => array(
                'title' => '离线地图',
                'description' => '该地图APP提供离线地图数据包下载，帮助用户节省流量。',
            ),
            'q3' => array(
                'title' => '地图数据的及时更新',
                'description' => '地图数据的及时更新包括地图上道路、桥梁、建筑物等信息尽可能功能的及时更新。不要几个月或者几年地面已经发生变化了，地图数据还是没有任何变化。',
            ),
            'q4' => array(
                'title' => '搜索目标地点',
                'description' => '当你要去一个目的地，你却不知道大致方位的时候，可以在地图APP中输入目的地的地名，APP就可以在地图搜索该地名，如果找到就在地图中标记出来，如果没有找到，则提示没有找到该地名。',
            ),
            'q5' => array(
                'title' => '即点即搜',
                'description' => '当你在浏览电子地图并对某一地点感兴趣时，可以点击该地点，然后该地图APP显示该地点的详细信息，包括地名，建筑物的类型等信息。',
            ),
            'q6' => array(
                'title' => '汽车驾驶路线规划',
                'description' => '当你想要从一个地点开车去一个另一个地点，你却不知道怎么走的时候，可以在地图APP中输入起始地点目的地点，APP就可以在地图搜索该起始地点和目的地点，如果找到他们，则在地图上以高亮显示从起始点到目的地的最优路线（距离最短），如果没有找到某一地点，则提示没有找到该地点，并提醒重新输入。',
            ),
            'q7' => array(
                'title' => '公交路线规划',
                'description' => '当你想要做公交车从一个地点去一个另一个地点，你却不知道怎么走的时候，可以在地图APP中输入起始地点目的地点，APP就可以在地图搜索该起始地点和目的地点，如果没有找到与输入地名匹配的公交站点，则提示没有找到该站点，并提醒重新输入，如果找到他们，则给出不同的可选路线，并显示不同路线的可能时间、距离、换乘方案、和站数等信息。',
            ),
            'q8' => array(
                'title' => '公交车动态提醒',
                'description' => '地图APP在显示推荐的公交路线时，显示推荐线路的公交到达起始站点所需的时间，地铁到达换乘站点所需的时间信息。这样可以让用户合理安排时间，同时在不同的线路见做出选择，比如可以选择等待时间最少的线路。',
            ),
            'q9' => array(
                'title' => '公交末班车提醒',
                'description' => '地图APP在显示推荐的公交路线时，相应的显示各个公交线路的末班车时间。',
            ),
            'q10' => array(
                'title' => '自行车路线规划',
                'description' => '当你想要骑自行车从一个地点去一个另一个地点，你却不知道怎么走的时候，可以在地图APP中输入起始地点目的地点，APP就可以给出不同的可选的自行车路线，并显示不同路线的可能时间和距离。',
            ),
            'q11' => array(
                'title' => '步行路线规划',
                'description' => '当你想要以步行的方式从一个地点去一个另一个地点，你却不知道怎么走的时候，可以在地图APP中输入起始地点目的地点，APP就可以给出不同的可选步行路线，并显示不同路线的可能时间和距离。',
            ),
            'q12' => array(
                'title' => '可设置途径点的路线规划',
                'description' => '当你想要以某种方式（驾车、步行、公交车、自行车）从一个地点去一个另一个地点，你不仅可以在地图APP中输入起始地点目的地点，还可以设置一些途径点（从起点到目的地点要经过的地点），APP就给出不同的可选路线，并显示不同路线的可能时间和距离等信息。 ',
            ),
            'q13' => array(
                'title' => '语音导航',
                'description' => '当你从某一起始点开始以软件规划的路线行驶（驾车、步行、或自行车）的时候，地图APP能够实时的在地图上显示你行进的位置，并在路口时以语音提醒你的转向方向。并且当你走错路时，软件能够以你当前地点为起始点重新规划你的路线并导航。',
            ),
            'q14' => array(
                'title' => '导航报告生成',
                'description' => '当你驾车并使用语言导航功能时，在导航结束（到达目的地或者用户结束导航）时，该地图APP出具导航报告，包括显示你的最高时速、平均时速、驾驶的安全状况等信息。 ',
            ),
            'q15' => array(
                'title' => '电子狗',
                'description' => '在驾驶汽车过程中，提前提醒用户前方电子眼、测试雷达以及急转弯等某些危险区域的存在。',
            ),
            'q16' => array(
                'title' => '实时路况显示',
                'description' => '当你从某一起始点开始以软件规划的路线驾车行驶的时候，地图APP能够实时的在地图上以不同颜色显示不同路段的拥堵状况。',
            ),
            'q17' => array(
                'title' => '路况电台',
                'description' => '当你驾车行驶的时候，地图APP能够根据你的地点实时以语音的形式播报前方路况的情况。 ',
            ),
            'q18' => array(
                'title' => '餐馆搜索',
                'description' => '根据你当前的位置，显示周边200米以内或者更大范围内的餐馆以及其位置、菜品、联系方式、内部照片等信息。',
            ),
            'q19' => array(
                'title' => '餐馆预定以及排队提醒',
                'description' => '用户可以通过该地图APP查找某一个餐馆并实时显示餐馆的预定情况。用户可以预定座位。当预定人很多时，可以实时提醒用户桌位的排队情况、以及需要等待的时间等信息。 ',
            ),
            'q20' => array(
                'title' => '酒店搜索以及预订',
                'description' => '根据用户当前的地理位置信息，显示周边200米或者更大范围内的酒店，包括各个酒店的位置、价格、星级水平、住宿预订情况。 用户也可以通过该地图APP来预订酒店。',
            ),
            'q21' => array(
                'title' => '团购',
                'description' => '该功能支持团购信息的发布，以及用户对团购活动的参与。',
            ),
            'q22' => array(
                'title' => '订火车票',
                'description' => '该功能支持用户查询、购买火车票。',
            ),
            'q23' => array(
                'title' => '加油站搜索',
                'description' => '根据用户当前的地理位置信息，显示周边200米或者更大范围内的加油站，包括加油站的位置、运行状况等信息。',
            ),
            'q24' => array(
                'title' => '卫生间搜索',
                'description' => '根据用户当前的地理位置信息，显示周边200米或者更大范围内的加油站，包括加油站的位置、运行状况等信息。',
            ),
            'q25' => array(
                'title' => '电影院搜索与座位预订',
                'description' => '根据用户当前的地理位置信息，显示周边200米或者更大范围内的电影院，包括电影院的位置、以及近日放映电影等信息。用户也可以通过该软件预订相应的座位。',
            ),
            'q26' => array(
                'title' => '叫车服务',
                'description' => '使用该功能，该APP可以发出叫车请求、以及你的地点给各个出租车司机。',
            ),
            'q27' => array(
                'title' => '寻找代驾',
                'description' => '使用该功能，该APP可以发出代驾请求、以及你的地点给各个代驾司机。',
            ),
            'q28' => array(
                'title' => '停车场搜索',
                'description' => '根据用户当前的地理位置信息，显示周边200米或者更大范围内的停车场，包括停车场的位置信息。',
            ),
            'q29' => array(
                'title' => '购物中心导购',
                'description' => '在地图搜索框输入想要去的购物中心，然后该地图APP显示该购物中心的位置等信息，同时用户也可以查看该购物中心各个楼层的商铺信息，比如服饰的品牌、以及餐馆信息。 ',
            ),
            'q30' => array(
                'title' => '房屋租赁',
                'description' => '该功能支持房屋拥有者发布房屋租赁信息，并可以上传照片、联系方式等信息；而房屋租赁者则可以搜索查看某一区域内的所有房屋信息，包括位置以及图片等信息。',
            ),
            'q31' => array(
                'title' => '街景服务',
                'description' => '通过该功能用户可以以三维身临其境的方式欣赏某一条街道、博物馆、餐馆、酒店大堂等真实状况。',
            ),
            'q32' => array(
                'title' => '时间胶囊',
                'description' => '通过该功能用户可以给未来留言。留言的对象可以是其他人，也可以是自己。通过设定一定的时间，留言对象可以在时间到达之后收听留言。',
            ),
            'q33' => array(
                'title' => '照片管理与分享',
                'description' => '通过该功能，该地图APP可以在地图上显示用户通过手机拍摄的照片（类似谷歌地图上显示照片的方式）。通过该功能，用户就可以清楚的知道每个照片的拍摄具体地点。同时，用户也设置各个照片的分享功能，如果用户分享了其某些照片，那么其分享对象（同样使用该地图APP用户）就可以在地图上看到你更新的照片。',
            ),
            'q34' => array(
                'title' => '搜索记录保存',
                'description' => '当用户在输入某一地点、餐馆进行搜索感兴趣区域或者进行导航时，该地图APP保存记录用户的输入。用户可以查看自己的搜索记录，同时在下次搜索输入时可以自动弹出以供选择。 ',
            ),
            'q35' => array(
                'title' => '数据同步',
                'description' => '通过该功能将在手机端的用户信息包括搜索记录、导航报告、管理的照片等数据同步保存到云端，增加数据安全性。',
            ),

        );

        return array('baseQuestions' => $baseQuestions, 'questions' => $questions);
    }
}
