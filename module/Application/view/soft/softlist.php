<?php
$developSoftInfos = array(
    'vim' => array(
        'officeWeb' => 'http://www.vim.org/',
        'version' => '7.4',
    ),
    'Git' => array(
        'officeWeb' => 'http://git-scm.com',
        'version' => '1.9.5',
    ),
    'TortoiseSVN' => array(
        'officeWeb' => 'http://tortoisesvn.net/',
        'version' => '1.8.10',
    ),
    'TortoiseGIT' => array(
        'officeWeb' => 'http://tortoisegit.org/',
        'version' => '1.8.12.0',
    ),
    'XAMPP' => array(
        'officeWeb' => 'https://www.apachefriends.org/',
        'version' => '5.6.3',
    ),
    'WinMerge' => array(
        'officeWeb' => 'http://winmerge.org/',
        'version' => '2.14.0',
    ),
);

$systemSoftInfos = array(
    'WinSCP' => array(
        'officeWeb' => 'http://winscp.net/',
        'version' => '5.5.6',
    ),
    'SecureCRT' => array(
        'officeWeb' => 'http://www.vandyke.com/',
        'version' => '7.3.1',
        'download' => 'http://www.tt7z.com/html/VanDyke-SecureCRT.html',
    ),
    'putty' => array(
        'officeWeb' => 'http://www.putty.org/',
        'version' => '0.63',
    ),
    'CentOS' => array(
        'officeWeb' => 'http://www.centos.org/',
        'version' => '6.5',
    ),
    'Windows8' => array(
        'officeWeb' => 'http://windows.microsoft.com/',
        'version' => '8.1',
    ),
    'Windows10' => array(
        'officeWeb' => 'http://windows.microsoft.com/',
        'version' => 'win10-preview',
    ),
    'VMware' => array(
        'officeWeb' => 'http://www.vmware.com/',
        'version' => '9.0.1',
    ),
    'LaoMaoTao' => array(
        'officeWeb' => 'http://www.laomaotao.org.cn/',
        'version' => 'v2014zhuangji',
    ),
);

$s360SoftInfos = array(
    '360zip', 'AdobeReader', 'MozillaFirefox', 'SogouPinyin', 
    'Tencent', 'Thunder', 'Chrome'
);

$otherSoftInfos = array(
    'LdapAdmin', 'OFFICE2007', 'UltraISO', 'MindManager', 
    'ietester', 'debugbar', 'foxmail', 'Axure', 'iusesvn_tool', 
    'mongodb', 'Robomongo', 'rubyinstaller', 'nodejs'
);

$softInfos = array(
    'system' => array(
        'name' => '系统相关软件',
        'description' => '系统安装软件，或跟系统相关的一些工具',
        'infos' => $systemSoftInfos,
    ),
    'develop' => array(
        'name' => '开发相关软件',
        'description' => '开发相关的软件和工具',
        'infos' => $developSoftInfos,
    ),
    's360' => array(
        'name' => '360软件管家',
        'description' => '直接由360软件管家负责维护和管理的软件',
        'infos' => $s360SoftInfos,
    ),
    'other' => array(
        'name' => '其他软件',
        'description' => '',
        'infos' => $otherSoftInfos,
    )
);
?>
<div class="page-header"><h1><?php echo $data['name']; ?></h1></div>

<?php foreach ($softInfos as $key => $softInfo) { ?> 
<h2><?php echo $softInfo['name']; ?></h2>
<p><?php echo $softInfo['description']; ?></p>
<table class="table table-bordered table-striped">
  <thead>
    <?php if (in_array($key, array('develop', 'system'))) { ?>
    <tr><th>软件</th><th>当前使用的版本</th><th>官网</th></tr>
    <?php } else { ?>
    <tr><th  colspan="4">软件列表</th></tr>
    <?php } ?>
  </thead>
  <tbody>
    <?php if (in_array($key, array('develop', 'system'))) { foreach($softInfo['infos'] as $name => $info) { ?>
    <tr>
      <td><?php echo $name; ?></td>
      <td><?php echo $info['version']; ?></td>
      <td><?php echo "<a href='{$info['officeWeb']}' target='_blank'>官网</a>"; if (isset($info['download'])) { echo "<a href='{$info['download']}' target='_blank'>下载</a>"; } ?></td>
    </tr>
    <?php } } else { $i = 0; foreach ($softInfo['infos'] as $soft) { if ($i / 4 == 0) { echo '<tr>'; } ?>
      <td><?php echo $soft; ?></td>
    <?php if ($i % 4 == 3) { echo '</tr>'; } $i++; } if ($i % 4 !=  3) { echo '</tr>'; } } ?>
  </tbody>
</table>
<?php } ?>
