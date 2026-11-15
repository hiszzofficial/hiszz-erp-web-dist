<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
if (($_GET['key'] ?? '') !== 'hiszz-deploy-2026') { http_response_code(403); echo '{"ok":false}'; exit; }
$src=__DIR__; $dst=dirname(__DIR__); $n=0;
$it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
foreach ($it as $item) {
  $rel=substr($item->getPathname(), strlen($src)+1);
  if ($rel===false||$rel===''||str_starts_with($rel,'.git')||$rel==='hiszz-promote-to-root.php') continue;
  $target=$dst.'/'.$rel;
  if ($item->isDir()) { if (!is_dir($target)) mkdir($target,0755,true); continue; }
  if (!is_dir(dirname($target))) mkdir(dirname($target),0755,true);
  if (!copy($item->getPathname(),$target)) { http_response_code(500); echo json_encode(['ok'=>false,'file'=>$rel]); exit; }
  $n++;
}
@unlink(__FILE__);
echo json_encode(['ok'=>true,'copied'=>$n,'marker'=>@file_get_contents($dst.'/hiszz-deploy-marker.txt')]);
