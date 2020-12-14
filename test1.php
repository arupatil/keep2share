<?php
// session_start();
// echo "hgfgh";
// // print_r($_SESSION);
// // $_SESSION['username'] = "patilarunash@gmail.com";
// // $_SESSION['password'] = "Puja$810";
// $ch = curl_init();

// $endpoint = 'https://k2s.cc/profile';
// $params = array('username' => 'patilarunash@gmail.com', 'password'=>'Puja$810');
// $url = $endpoint . '?' . http_build_query($params);
// curl_setopt($ch, CURLOPT_URL, $url);


// // curl_setopt($ch, CURLOPT_URL, 'https://k2s.cc/profile');
// // curl_setopt($ch, CURLOPT_POSTFIELDS, 'username=patilarunash@gmail.com&password=Puja$810');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// $contents = curl_exec($ch);
// print_r($contents);

// {
// 		"_id" : ObjectId("5fa4f7e13bff884908659b4b"),
//         "type" : 1,
//         "pp" : "vgs82345f142f86a1c004:47791622",
//         "score" : 900,
//         "ts" : 1599566768
// }

// {
//     _ID: "fEED id"
//         likeCount: 0,
//            sharecount:0
//         comments_count:0 +1/-1 based upon adding and deleting
//         comments: [{
//             _id : 1
//             cp ://commented player
//             parentid:[] // empty means under root comment
//             content: // Posted String
//             tp:[player_id's]
//             like :[player id's]
//             like_count: 0
//             createdOn:
//           },
//           {
//             _id : 2
//             player id :
//             feed id: // not required if we add under feeds
//             parent id:[1] // empty means under root comment
//             content:
//             like :[player id's]
//             createdOn:
//           },
//         ]
// }
 
    public function addComment() : bool {

    }
 	public static function setPost($gameid, $feedRequest, $feedType): bool
    {
        $feedData = array();
        $playerid = Common::$playerGloDocument['playerid'];
        if ($feedType == 2) {
            if (isset($feedRequest['image1']) && $feedRequest['image1'] != ' ' && !empty($feedRequest['image1'])) {
                $imgPath = Config::$SITE_URL_UPLOAD_IMAGE . '/FeedImages/' . $feedType . '/';
                Common::getLogger()->debug("Feed::setPost: The image1 array is " . json_encode($_FILES[$feedRequest['image1']]));
                $file_name = $_FILES[$feedRequest['image1']]['name'];
                $file_size = $_FILES[$feedRequest['image1']]['size'];
                $file_type = $_FILES[$feedRequest['image1']]['type'];
                $temp_name = $_FILES[$feedRequest['image1']]['tmp_name'];
                $fName = array();
                $fName = explode('.', $_FILES[$feedRequest['image1']]['name']);
                $i = 0;
                $new_file_name = 'player_' . $playerid . '.' . $fName[sizeof($fName) - 1];
                while (file_exists($imgPath . $new_file_name)) {
                    $new_file_name = 'player_' . $playerid . '_' . $i . '.' . $fName[sizeof($fName) - 1];
                    $i++;
                }
                $new_file_name = trim($new_file_name);
                $moveStatus = move_uploaded_file($temp_name, $imgPath . $new_file_name);
                if (!$moveStatus) {
                    Common::$logger->write("setPost: : Cannot move video file to location $imgPath", MLogger::DEBUG);
                }
                $feedData['image'] = '/FeedImages/' . $feedType . '/' . $new_file_name;
            }
            if (isset($feedRequest['video1']) && !empty($feedRequest['video1']) && $feedRequest['video1'] != '') {
                Common::getLogger()->debug("Feed::setPost: The video1 array is " . json_encode($_FILES[$feedRequest['video1']]));
                $file_name = $_FILES[$feedRequest['video1']]['name'];
                $file_size = $_FILES[$feedRequest['video1']]['size'];
                $file_type = $_FILES[$feedRequest['video1']]['type'];
                $temp_name = $_FILES[$feedRequest['video1']]['tmp_name'];
                Common::$logger->write("setPost: : video tmp path is " . print_r($_FILES, true), MLogger::DEBUG);
                $videoPath = Config::$SITE_URL_UPLOAD_IMAGE . '/FeedVideos/' . $feedType . '/';
                $fName = array();
                $fName = explode('.', $_FILES[$feedRequest['video1']]['name']);
                $i = 0;
                $new_file_name = 'player_' . $playerid . '.' . $fName[sizeof($fName) - 1];
                while (file_exists($videoPath . $new_file_name)) {
                    $new_file_name = 'player_' . $playerid . '_' . $i . '.' . $fName[sizeof($fName) - 1];
                    $i++;
                }
                $new_file_name = trim($new_file_name);
                $moveStatus = move_uploaded_file($temp_name, $videoPath . $new_file_name);
                if (!$moveStatus) {
                    Common::$logger->write("setPost: : Cannot move video file to location $videoPath", MLogger::DEBUG);
                }
                $feedData['video'] = '/FeedVideos/' . $feedType . '/' . $new_file_name;
            }
            if (isset($feedRequest['text1']) && !empty($feedRequest['text1']) && $feedRequest['text1'] != ' ') {
                $feedData['text'] = $feedRequest['text1'];
            }
            if ((isset($feedData['text']) && !empty($feedData['text'])) || (isset($feedData['image']) && !empty($feedData['image'])) || (isset($feedData['video']) && !empty($feedData['video']))) {
                $feedData['type'] = $feedType;
                $feedData['ts'] = round(intval($feedRequest['ts']) / 1000);
                $feedData['pp'] = Common::$playerGloDocument['playerid'];
                if (isset($feedRequest['wtratio']) && $feedRequest['wtratio'] != ' ' && !empty($feedRequest['wtratio'])) {
                    $feedData['wtratio'] = $feedRequest['wtratio'];
                }
                if (isset($feedRequest['htratio']) && $feedRequest['htratio'] != ' ' && !empty($feedRequest['htratio'])) {
                    $feedData['htratio'] = $feedRequest['htratio'];
                }
                if (isset($feedRequest['ht']) && !empty($feedRequest['ht']) && $feedRequest['ht'] !== '') {
                    $feedData['ht'] = $feedRequest['ht'];
                }
                if (isset($feedRequest['wt']) && !empty($feedRequest['wt']) && $feedRequest['wt'] !== '') {
                    $feedData['wt'] = $feedRequest['wt'];
                }
            }
        } elseif ($feedType == 3) {
            if (isset($feedRequest['text1']) && !empty($feedRequest['text1']) && $feedRequest['text1'] != ' ') {
                $feedData['type'] = $feedType;
                $feedData['ts'] = round(intval($feedRequest['ts']) / 1000);
                $feedData['pp'] = Common::$playerGloDocument['playerid'];
                $feedData['text'] = $feedRequest['text1'];
            }
        } elseif ($feedType == 4) {
            if (isset($feedRequest['image1']) && !empty($feedRequest['image1']) && $feedRequest['image1'] != ' ') {

                $imgPath = Config::$SITE_URL_UPLOAD_IMAGE . '/FeedImages/' . $feedType . '/';
                $file_name = 'player_' . $playerid . '.png';
                $fullImgPath = '/FeedImages/' . $feedType . '/' . 'player_' . $playerid . '.png';
                $base64decodedImg = base64_decode($feedRequest['image1'], true);
                if ($base64decodedImg === false) {
                    $base64decodedImg = base64_decode(urldecode($feedRequest['image1']), true);
                }
                if ($base64decodedImg !== false) {
                    $feedData['type'] = $feedType;
                    $feedData['ts'] = round(intval($feedRequest['ts']) / 1000);
                    $feedData['pp'] = Common::$playerGloDocument['playerid'];
                    $shareImg = imagecreatefromstring($base64decodedImg);
                    $i = 0;
                    $new_file_name = $file_name;
                    while (file_exists($imgPath . $new_file_name)) {
                        $new_file_name = 'player_' . $playerid . '_' . $i . '.png';
                        $i++;
                    }
                    $new_file_name = trim($new_file_name);
                    $fullImgPath = '/FeedImages/' . $feedType . '/' . $new_file_name;
                    imagepng($shareImg, $imgPath . $new_file_name);
                    $feedData['image'] = $fullImgPath;
                    if (isset($feedRequest['wtratio']) && $feedRequest['wtratio'] != ' ' && !empty($feedRequest['wtratio'])) {
                        $feedData['wtratio'] = $feedRequest['wtratio'];
                    }
                    if (isset($feedRequest['htratio']) && $feedRequest['htratio'] != ' ' && !empty($feedRequest['htratio'])) {
                        $feedData['htratio'] = $feedRequest['htratio'];
                    }
                    if (isset($feedRequest['ht']) && !empty($feedRequest['ht']) && $feedRequest['ht'] !== '') {
                        $feedData['ht'] = $feedRequest['ht'];
                    }
                    if (isset($feedRequest['wt']) && !empty($feedRequest['wt']) && $feedRequest['wt'] !== '') {
                        $feedData['wt'] = $feedRequest['wt'];
                    }
                }
            }
        } elseif ($feedType == 5) {
            if (isset($feedRequest['video1']) && !empty($feedRequest['video1']) && $feedRequest['video1'] != ' ') {
                $feedData['type'] = $feedType;
                $feedData['ts'] = round(intval($feedRequest['ts']) / 1000);
                $feedData['pp'] = Common::$playerGloDocument['playerid'];
                $file_name = $_FILES['uFile']['name'];
                $file_size = $_FILES['uFile']['size'];
                $file_type = $_FILES['uFile']['type'];
                $temp_name = $_FILES['uFile']['tmp_name'];
                $videoPath = Config::$SITE_URL_UPLOAD_IMAGE . '/FeedVideos/' . $feedType . '/';
                $fName = array();
                $fName = explode('.', $_FILES['uFile']['name']);
                $i = 0;
                $new_file_name = 'player_' . $playerid . '.' . $fName[sizeof($fName) - 1];
                while (file_exists($videoPath . $new_file_name)) {
                    $new_file_name = 'player_' . $playerid . '_' . $i . '.' . $fName[sizeof($fName) - 1];
                    $i++;
                }
                $new_file_name = trim($new_file_name);
                move_uploaded_file($temp_name, $videoPath . $new_file_name);
                $feedData['video'] = '/FeedVideos/' . $feedType . '/' . $new_file_name;
                if (isset($feedRequest['wtratio']) && $feedRequest['wtratio'] != ' ' && !empty($feedRequest['wtratio'])) {
                    $feedData['wtratio'] = $feedRequest['wtratio'];
                }
                if (isset($feedRequest['htratio']) && $feedRequest['htratio'] != ' ' && !empty($feedRequest['htratio'])) {
                    $feedData['htratio'] = $feedRequest['htratio'];
                }
                if (isset($feedRequest['ht']) && !empty($feedRequest['ht']) && $feedRequest['ht'] !== '') {
                    $feedData['ht'] = $feedRequest['ht'];
                }
                if (isset($feedRequest['wt']) && !empty($feedRequest['wt']) && $feedRequest['wt'] !== '') {
                    $feedData['wt'] = $feedRequest['wt'];
                }
            }
        }
        if(!empty($feedRequest['parentid'] ))
        {
        	//it mean it will follow under the feed
        	//find the exsting feed
        	try {
        		if (substr($gameid, 0, 2) === "gm") {
        			
        		}
        		else if(substr($gameid, 0, 2) === "cn") {
        			
        		}
        		else {
	                Common::$respParams["reqQuery"] = array(
	                    "status" => "failure",
	                    "msg" => "Internal Error",
	                    "statuscode" => Common::STATUS_INTERNAL_ERROR
	                );
	                return false;
	            }
        	} catch(Exception $ex) {

        	}
        }
        else {
        	//parentId empty
        	//create new feed
        	try {
        		if (substr($gameid, 0, 2) === "gm") {
	                if (Common::$gameDocument['isGame'] == false) {
	                    if (in_array($gameid, Common::$gameDocument['iAppIDs'])) {
	                        if (sizeof($feedData) > 0) {
	                            $bStatus = Common::$mongoDB
	                                ->selectCollection("feed_" . $gameid)
	                                ->insertOne($feedData);
	                            if ($bStatus) {
	                                if (isset(self::$gameInfo['iAppIDs']) && sizeof(self::$gameInfo['iAppIDs']) > 0) {
	                                    foreach (self::$gameInfo['iAppIDs'] as $ContentID) {
	                                        $feedData['gameid'] = $gameid;
	                                        $cntntStatus = Common::$mongoDB
	                                            ->selectCollection("content_" . $ContentID)
	                                            ->insertOne($feedData);
	                                    }
	                                }
	                            }
	                            //pushing to master plugin as event done
	                            $eventData = array();
	                            $eventData['feedType'] = $feedType;
	                            $eventData['gameID'] = $gameid;
	                            $pluginEvent = MasterPlugin::EventDone(MasterPlugin::EVENT_SUBMIT_FEED_DATA, $eventData);
	                        } else {
	                            Common::$respParams["reqQuery"] = array(
	                                "status" => "failure",
	                                "msg" => "Insufficient data sent",
	                                "statuscode" => Common::STATUS_FEED_INSUFFICIENT_DATA
	                            );
	                            return false;
	                        }
	                    } else {
	                        Common::$respParams["reqQuery"]['devmsg'][] = "Content App Does not Contain this GameID:" . $gameid;
	                        return true;
	                    }
	                } else {
	                    if (sizeof($feedData) > 0) {
	                        $bStatus = Common::$mongoDB
	                            ->selectCollection("feed_" . $gameid)
	                            ->insertOne($feedData);
	                        if ($bStatus) {
	                            if (isset(self::$gameInfo['iAppIDs']) && sizeof(self::$gameInfo['iAppIDs']) > 0) {
	                                foreach (self::$gameInfo['iAppIDs'] as $ContentID) {
	                                    $feedData['gameid'] = $gameid;
	                                    $cntntStatus = Common::$mongoDB
	                                        ->selectCollection("content_" . $ContentID)
	                                        ->insertOne($feedData);
	                                }
	                            }
	                        }
	                    } else {
	                        Common::$respParams["reqQuery"] = array(
	                            "status" => "failure",
	                            "msg" => "Insufficient data sent",
	                            "statuscode" => Common::STATUS_FEED_INSUFFICIENT_DATA
	                        );
	                        return false;
	                    }
	                }
	            } elseif (substr($gameid, 0, 2) === "cn") {
	                if (sizeof($feedData) > 0) {
	                    $feedData['gameid'] = $gameid;
	                    $bStatus = Common::$mongoDB
	                        ->selectCollection("content_" . $gameid)
	                        ->insertOne($feedData);
	                } else {
	                    Common::$respParams["reqQuery"] = array(
	                        "status" => "failure",
	                        "msg" => "Insufficient data sent",
	                        "statuscode" => Common::STATUS_FEED_INSUFFICIENT_DATA
	                    );
	                    return false;
	                }
	            } else {
	                Common::$respParams["reqQuery"] = array(
	                    "status" => "failure",
	                    "msg" => "Internal Error",
	                    "statuscode" => Common::STATUS_INTERNAL_ERROR
	                );
	                return false;
	            }
        	} catch( Exception $ex) {
        		Common::$logger->write("setPost: : Exception encountered while inserting feed:" . print_r($ex, true), MLogger::ERR);
	            Common::$logger->flush();
	            Common::$respParams["reqQuery"] = array(
	                "status" => "failure",
	                "msg" => "Internal Error",
	                "statuscode" => Common::STATUS_INTERNAL_ERROR
	            );
	            return false;
        	} 
        }
        Common::$respParams["reqQuery"]['statuscode'] = Common::STATUS_OK;
        return true;
    }