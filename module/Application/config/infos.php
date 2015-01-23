<?php
$docs = array(
    'structure_algorithm' => array(
        'name' => '数据结构和算法',
        'url' => 'document/structure_algorithm',
    ),
    'design' => array(
        'name' => '设计模式',
        'url' => 'document/design',
    ),
    'mysql' => array(
        'name' => 'MySQL使用手记',
        'url' => 'document/mysql',
    ),
    'senior' => array(
        'name' => 'PHP高级编程',
        'url' => 'document/senior',
    ),
);
$front = array(
    'bootstrap' => array(
        'name' => '官方示例列表',
        'url' => 'bootstrap/demo',
    ),
    'bootstrap_demo1' => array(
        'name' => '官方示例',
        'url' => 'bootstrap/theme',
    ),
    'plugin' => array(
        'name' => '前端插件',
        'url' => 'bootstrap/plugin',
    ),
    'plugin_demo1' => array(
        'name' => '插件示例',
        'url' => 'bootstrap/plugin',
    ),
);
$frame = array(
    'softlist' => array(
        'name' => '软件汇总',
        'url' => 'soft',
    ),
    'phuml' => array(
        'name' => '常见UML图',
        'url' => 'codelib/phumlshow',
    ),
    'survey' => array(
        'name' => '投票小应用',
        'url' => 'lbs/survey',
    ),
    'domain' => array(
        'name' => '域名管理小应用',
        'url' => 'codelib/domain',
    ),
);

$infos = array(
    'docs' => array(
        'title' => '文档系统',
        'url' => 'docs',
        'description' => '基于markdown轻量级标记语言，把工作和学习过程中的知识体系结构以文档的形式表现出来。',
        'elems' => $docs
    ),
    'front' => array(
        'title' => 'Web前端',
        'url' => 'bootstrap',
        'description' => '基于boostrap和jquery，搜集整理Web前端相关的各种资源和插件',
        'elems' => $front
    ),
    'frame' => array(
        'title' => 'lightFrame框架',
        'url' => 'lightFrame',
        'description' => '自主研发的基于PHP5.3以上版本的PHP轻量级框架',
        'elems' => $frame
    ),
);

return $infos;
