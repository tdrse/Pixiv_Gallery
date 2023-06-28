<?php
/*
By tdrse
Version: 2.4.1
API By https://pixiv-ab.tk/api/pixiv/ (Pixiv-AB)
Github: https://github.com/tdrse/Pixiv_Gallery/
*/
date_default_timezone_set('UTC');
$type=@$_GET['type'];
$date=@$_GET['date'];
//$user=@$_GET['user'];//+
$uid=@$_GET['uid'];
$id=@$_GET['id'];
$u=explode("user=",$_SERVER['QUERY_STRING'])[1];
$e=substr(explode("user=",$_SERVER['QUERY_STRING'])[0],-1,1);
if($e=='&' || !$e){$user_q=substr($u,0,(strpos($u,'&') ? strpos($u,'&') : mb_strlen($u)));}else{$user_q='';};
$user=urldecode(str_replace('+','%2B',$user_q));
$request=$id ? 'id' : ($user ? 'user/name' : ($uid ? 'user/id' : 'date'));
$init=$id ? $id : ($user ? $user : ($uid ? $uid : ($date ? date('Y-m-d',strtotime($date)) : date('Y-m-d',time()-86400))));
$req_type=$id ? 'Id' : ($user ? 'User' : ($uid ? 'Uid' : 'Date'));
$url_type=$type ? '&type='. $type : '';
$url_index_type=$type ? 'type='. $type : '';
$type=$id ? '' : ($type ? $type : 'daily');
$url='https://pixiv-ab.tk/api/pixiv';
$api=$url .'/'. $type .'/'. $request .'/'. str_replace('+',' ',urlencode($init));
$arrcp=['ssl'=>['verify_peer'=>false,'verify_peer_name'=>false]];
$json=@file_get_contents($api,false,stream_context_create($arrcp));
$jd=json_decode($json);
$list=@count($jd,0);
if(@$_GET['id']){
$title=$jd->{'title'};
$tags=$jd->{'tags'};
$tags='<a style="color:#fff;background:#000;border:0px solid #000;line-height:20px;">'. @implode('</a>&nbsp;<a style="color:#fff;background:#000;border:0px solid #000;line-height:20px;">',$tags) .'</a>';
$url=$jd->{'url'};
$artist=$jd->{'artist'};
$id=$jd->{'id'};
$uid=$jd->{'uid'};
}else{
for($i=0;$i<@count($jd);$i++){
$title=$jd[$i]->{'title'};
$tags=$jd[$i]->{'tags'};
//$tags='<a style="color:#fff;background:#000;border:0px solid #000;line-height:20px;">'. implode('</a>&nbsp;<a style="color:#fff;background:#000;border:0px solid #000;line-height:20px;">',$tags) .'</a>';
$url=$jd[$i]->{'url'};
$url_host=substr($url,0,strpos($url,'//')+2) . substr($url,strpos($url,'//')+2,strpos(substr($url,strpos($url,'//')+2),'/')+1);
$url_min=$url_host .'c/540x540_70/'. substr($url,mb_strlen($url_host));
//count(str_split($url_host));
$url_path='img-original'. substr($url,strpos($url,'img-master')+10);
$url_ori=$url_host . substr($url_path,0,strrpos($url_path,'_')) . substr($url_path,strrpos($url_path,'.'));
$artist=$jd[$i]->{'artist'};
$id=$jd[$i]->{'id'};
$uid=$jd[$i]->{'uid'};
if($i%2==0){
//onclick="window.open(\''. $url .'\');";
$p=$p .'<div style="margin:4% auto;width:100%;box-sizing:border-box;border:0px solid #000;font-size:13px;line-height:16px;word-break:break-all;word-wrap:break-word;background:#fff;box-shadow:0px 0px 2px 0px rgba(0,0,0,.2);" ondblclick="window.open(\'?id='. $id .'\');"><img style="width:100%;" src="'. $url_min .'" onerror="this.src=\'./err.webp\';"><div style="padding:5px;">'. $title .'<br>['. $artist .'/'. $uid .']<br>['. $id .']</div></div>';
//<br>Tags: $tags;
}else{
$v=$v .'<div style="margin:4% auto;width:100%;box-sizing:border-box;border:0px solid #000;font-size:13px;line-height:16px;word-break:break-all;word-wrap:break-word;background:#fff;box-shadow:0px 0px 2px 0px rgba(0,0,0,.2);" ondblclick="window.open(\'?id='. $id .'\');"><img style="width:100%;" src="'. $url_min .'" onerror="this.src=\'./err.webp\';"><div style="padding:5px;">'. $title .'<br>['. $artist .'/'. $uid .']<br>['. $id .']</div></div>';
};};};
echo '<html><meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover,user-scalable=no,target-densitydpi=device-dpi">';
echo '<title>Pixiv Gallery | '. $req_type .'&nbsp;['. $init .']</title>';
echo '<div style="display:flex;position:fixed;top:0px;left:0px;width:100%;height:50px;box-sizing:border-box;border:0px solid #000;background:none;box-shadow:0px 0px 2px 0px rgba(0,0,0,.2);z-index:1;"><div style="user-select:none;font-size:24px;line-height:26px;padding:12px;" ondblclick="location=\'?'. $url_index_type .'\';">Pixiv Gallery</div></div>';
echo '<div style="display:flex;position:fixed;bottom:0px;left:0px;width:100%;height:50px;user-select:none;box-sizing:border-box;border:0px solid #000;background:none;box-shadow:0px 0px 2px 0px rgba(0,0,0,.2);z-index:1;">';
if($request=='date'){
echo '<a style="-webkit-text-size-adjust:none;-webkit-tap-highlight-color:rgba(0,0,0,.2);color:#000;text-decoration:none;padding:15px;width:50%;height:50px;" href="?date='. date('Y-m-d',strtotime($init)-86400) . $url_type .'"><div style="width:100%;padding:0px;text-align:center;font-size:18px;line-height:20px;border:0px solid #f00;">Prev</div></a><a style="-webkit-text-size-adjust:none;-webkit-tap-highlight-color:rgba(0,0,0,.2);color:#000;text-decoration:none;padding:15px;width:50%;height:50px;" href="?date='. date('Y-m-d',strtotime($init)+86400) . $url_type .'"><div style="width:100%;padding:0px;text-align:center;font-size:18px;line-height:20px;border:0px solid #f00;">Next</div></a>';
}else if(@$_GET['id']){
echo '<a style="-webkit-text-size-adjust:none;-webkit-tap-highlight-color:rgba(0,0,0,.2);color:#000;text-decoration:none;padding:15px;width:100%;height:50px;" href="//pixiv.re/'. $id .'.png"><div style="width:100%;padding:0px;text-align:left;font-size:18px;line-height:20px;border:0px solid #f00;">Open Original Image</div></a>';
}else{
echo '<a style="-webkit-text-size-adjust:none;-webkit-tap-highlight-color:rgba(0,0,0,.2);color:#000;text-decoration:none;padding:15px;width:100%;height:50px;"><div style="width:100%;padding:0px;text-align:left;font-size:18px;line-height:20px;border:0px solid #f00;">List: '. $list .'</div></a>';}
echo '</div>';
echo '<div style="display:flex;position:fixed;top:50px;right:0px;width:100%;height:calc(100% - 100px);box-sizing:border-box;border:0px solid #f00;background:#f7f8fc;overflow:auto;">';
if(!$json){
echo '<div style="position:absolute;margin:2%;padding:10px;width:96%;box-sizing:border-box;border:0px solid #000;font-size:16px;line-height:24px;word-break:break-all;word-wrap:break-word;background:#fff;box-shadow:0px 0px 2px 0px rgba(0,0,0,.2);">No Data<br>Req: '. $req_type .'['. $init .']' . (@$_GET['id'] ? '' : '<br>Type: '. $type) .'<br>Request Api: <u>'. $api .'</u></div>';
}else if(@$_GET['id']){
echo '<div style="position:absolute;left:2%;width:96%;box-sizing:border-box;border:0px solid #000;"><div style="margin:2% auto;width:100%;box-sizing:border-box;border:0px solid #000;font-size:16px;line-height:24px;word-break:break-all;word-wrap:break-word;background:#fff;box-shadow:0px 0px 2px 0px rgba(0,0,0,.2);"><img style="width:100%;" src="'. $url .'" onerror="this.src=\'./err.webp\';"><div style="padding:5px;">'. $title .'<br>['. $artist .'/'. $uid .']<br>['. $id .']<br>'. $tags .'</div></div></div>';
}else{
echo '<div id="partleft" style="position:absolute;left:2%;width:47%;box-sizing:border-box;border:0px solid #000;">'. $p .'</div>';
echo '<div id="partright" style="position:absolute;right:2%;width:47%;box-sizing:border-box;border:0px solid #000;">'. $v .'</div>';}
echo '</div>';
echo '</html>';
?>
