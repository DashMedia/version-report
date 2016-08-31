<?php
/**
 * @name versionReport
 * @description This is an example plugin.  List the events it attaches to in the PluginEvents.
 * @PluginEvents OnManagerPageInit,OnWebPageInit,OnBeforeCacheUpdate
 */

// Your core_path will change depending on whether your code is running on your development environment
// or on a production environment (deployed via a Transport Package).  Make sure you follow the pattern
// outlined here. See https://github.com/craftsmancoding/repoman/wiki/Conventions for more info
$core_path = $modx->getOption('versionreport.core_path', null, MODX_CORE_PATH.'components/versionreport/');
include_once $core_path .'vendor/autoload.php';

$debug = false;
$newTimestamp = false;
$eventName = $modx->event->name;

if($eventName === 'OnManagerPageInit' || $eventName === 'OnWebPageInit'){

	$nextReport = $modx->getObject('modSystemSetting', 'versionreport.next_report');
	$nextReportVal = $nextReport->get('value');

	if($debug){
		$nextReportVal = 0;
	}

	if(time() > $nextReportVal){
		$json_key_file = $core_path . 'config/private-key.json';

		try{
			$api_key = $modx->getOption('versionreport.api_key');
			if(empty($api_key) || !file_put_contents($json_key_file, $api_key)){
				throw new Exception("File write error, can not create json key file. System setting for key may be empty");
			}
			$obj_client = GDS\Gateway\GoogleAPIClient::createClientFromJson($json_key_file);
			$obj_gateway = new GDS\Gateway\GoogleAPIClient($obj_client, 'what-does-the-drone-say');
			$obj_store = new GDS\Store('MODX', $obj_gateway);

			$data = new GDS\Entity();
			$data->setKeyName($modx->getOption('site_url'));
			$data->version = $modx->getOption('settings_version');
			$data->timestamp = date('c');


			$obj_store->upsert($data);
			
			$nextReport->set('value',strtotime('+7 days', time()));
			$nextReport->save();

		} catch (Exception $e){
			$modx->log(MODX::LOG_LEVEL_ERROR,'Version Report Error: ' . $e->getMessage());
		}
	}
}

if($eventName === 'OnBeforeCacheUpdate'){
	//force new data after cache clear
	$nextReport = $modx->getObject('modSystemSetting', 'versionreport.next_report');
	$nextReport->set('value',"0");
	$nextReport->save();
}