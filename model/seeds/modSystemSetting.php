<?php
/*-----------------------------------------------------------------
 * Lexicon keys for System Settings follows this format:
 * Name: setting_ + $key
 * Description: setting_ + $key + _desc
 -----------------------------------------------------------------*/
return array(

    array(
        'key'  		=>     'versionreport.next_report',
		'value'		=>     '0',
		'xtype'		=>     'textfield',
		'namespace' => 'versionreport',
		'area' 		=> 'versionreport:default'
    ),
  //   array(
  //       'key'  		=>     'versionreport.api_endpoint',
		// 'value'		=>     '',
		// 'xtype'		=>     'textfield',
		// 'namespace' => 'versionreport',
		// 'area' 		=> 'versionreport:default'
  //   ),
    array(
        'key'       =>     'versionreport.api_key',
        'value'     =>     '',
        'xtype'     =>     'textfield',
        'namespace' => 'versionreport',
        'area'      => 'versionreport:default'
    ),
);
/*EOF*/