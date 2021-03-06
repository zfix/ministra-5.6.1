<?php

require __DIR__ . '/common.php';
use Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89;
use Ministra\Lib\S642b6461e59cef199375bfb377c17a39\J314c10aa912ede885c71176b652431a5;
use Ministra\Lib\RemotePvr;
use Ministra\Lib\TvArchive;
use Ministra\Lib\User;
use Ministra\Lib\Vod;
if (empty($_GET['lid'])) {
    \header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    exit;
}
$link = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\L18e6d54d6202a6e70c8e428830aa4c89::getInstance()->from('download_links')->where(['link_hash' => $_GET['lid']])->get()->first();
if (empty($link)) {
    \header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    exit;
}
$user = \Ministra\Lib\User::getInstance((int) $link['uid']);
\ob_start();
if ($link['type'] == 'tv_archive') {
    $tv_archive = new \Ministra\Lib\TvArchive();
    try {
        $url = $tv_archive->getUrlByProgramId($link['media_id']);
    } catch (\Exception $e) {
        \header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        exit;
    }
    if ($url) {
        \header('Location: ' . $url);
        \ob_end_clean();
        exit;
    }
} elseif ($link['type'] == 'vclub') {
    $video = \Ministra\Lib\Vod::getInstance();
    try {
        $url = $video->getUrlByVideoId($link['media_id'], (int) $link['param1']);
    } catch (\Exception $e) {
        \header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        exit;
    }
    if (\preg_match('/(\\S+:\\/\\/\\S+)/', $url, $match)) {
        $url = $match[1];
    }
    if ($url) {
        \header('Location: ' . $url);
        \ob_end_clean();
        exit;
    }
} elseif ($link['type'] == 'pvr') {
    $user = \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\J314c10aa912ede885c71176b652431a5::z55bad8d1e166d966765492584ab3ab41((int) $link['uid']);
    if (empty($user)) {
        \header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        exit;
    }
    $pvr = new \Ministra\Lib\RemotePvr();
    $recording = $pvr->getById($link['media_id']);
    if (empty($recording)) {
        \header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        exit;
    }
    if ($recording['uid'] != $link['uid']) {
        \header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        exit;
    }
    try {
        $url = $pvr->getUrlByRecId($link['media_id']);
    } catch (\Exception $e) {
        \header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        exit;
    }
    if (\preg_match('/(\\S+:\\/\\/\\S+)/', $url, $match)) {
        $url = $match[1];
    }
    if ($url) {
        \header('Location: ' . $url);
        \ob_end_clean();
        exit;
    }
}
\header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
exit;
