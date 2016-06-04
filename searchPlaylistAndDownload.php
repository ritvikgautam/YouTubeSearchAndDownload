<?php

  //Initialize the following variables.
  $PLAYLIST_ID = "";
  $DEVELOPER_KEY = "";
  $YOUTUBE_DL_OPTIONS = "";

  $pageToken = "";
  $videoID = [];
  do
  {

  $apiURL = "https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=50&playlistId=" . $PLAYLIST_ID . "&key=" . $DEVELOPER_KEY . $pageToken;

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
    if($JSONResponse['items'][$i]['id']['kind'] == "youtube#playlistItem")
    {
      $thisVideoID = $JSONResponse['items'][$i]['contentDetails']['videoId'];
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
