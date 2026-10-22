<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');
try {
    $posterId=(int)($_GET['poster_id']??0);
    if($posterId<1){http_response_code(422);echo json_encode(['error'=>'poster_id is required']);exit;}
    $stmt=db()->prepare("SELECT p.id,p.name,p.target_file,p.target_status,h.id hotspot_id,h.label,h.x,h.y,h.width,h.height,h.z_index,h.video_url,h.content_type,a.id attraction_id,a.name attraction_name,a.short_description,a.description,a.main_image,a.maps_url,a.youtube_url,a.opening_hours FROM ar_posters p LEFT JOIN ar_hotspots h ON h.poster_id=p.id LEFT JOIN attractions a ON a.id=h.attraction_id WHERE p.id=? AND p.status='active'");
    $stmt->execute([$posterId]); $rows=$stmt->fetchAll(); if(!$rows){http_response_code(404);echo json_encode(['error'=>'Poster not found']);exit;}
    $first=$rows[0]; $hotspots=[]; foreach($rows as $r){if($r['hotspot_id'])$hotspots[]=['id'=>(int)$r['hotspot_id'],'label'=>$r['label'],'x'=>(float)$r['x'],'y'=>(float)$r['y'],'width'=>(float)$r['width'],'height'=>(float)$r['height'],'zIndex'=>(int)$r['z_index'],'videoUrl'=>$r['video_url'],'contentType'=>$r['content_type'],'attraction'=>$r['attraction_id']?['id'=>(int)$r['attraction_id'],'name'=>$r['attraction_name'],'shortDescription'=>$r['short_description'],'description'=>$r['description'],'image'=>$r['main_image'],'mapsUrl'=>$r['maps_url'],'youtubeUrl'=>$r['youtube_url'],'openingHours'=>$r['opening_hours']]:null];}
    echo json_encode(['poster'=>['id'=>(int)$first['id'],'name'=>$first['name'],'targetFile'=>$first['target_file'],'targetStatus'=>$first['target_status'],'hotspots'=>$hotspots]]);
} catch(Throwable $e){http_response_code(500);echo json_encode(['error'=>'Service unavailable']);}
