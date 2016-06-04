<?php

  //Initialize the following variables.
  $CHANNEL_ID = "";
  $DEVELOPER_KEY = "";
  $YOUTUBE_DL_OPTIONS = "";


  $pageToken = "";
  $videoID = [];
  do
  {

  $apiURL = "https://www.googleapis.com/youtube/v3/search?key=" . $DEVELOPER_KEY . "&channelId=" . $CHANNEL_ID ."&part=id&maxResults=50" . $pageToken;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $apiURL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 Gecko/20080311 Firefox/2.0.0.1');
  $apiResponse = curl_exec($ch);
  curl_close($ch);

  $JSONResponse = json_decode($apiResponse, true);

  if($JSONResponse['nextPageToken'])
    $pageToken = "&pageToken=" . $JSONResponse['nextPageToken'];

  for($i = 0; $i < $JSONResponse['pageInfo']['resultsPerPage']; $i++)
  {
    if($JSONResponse['items'][$i]['id']['kind'] == "youtube#video")
    {
      $thisVideoID = $JSONResponse['items'][$i]['id']['videoId'];
      array_push($videoID, $thisVideoID);
    }
  }

  } while($JSONResponse['nextPageToken']);

  $downloadVideos = "youtube-dl";

  foreach($videoID as $aVideoID)
  {
    $downloadCommand = $downloadVideos . " " . $aVideoID . " " . $YOUTUBE_DL_OPTIONS;
    system($downloadCommand);
  }
?>
