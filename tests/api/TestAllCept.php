<?php 
$I = new ApiTester($scenario);
$I->wantTo('perform actions and see result');

$I->sendGET('clear');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CREATE
$I->sendPOST('api/object/', ['_id' => '123456789', 'name' => 'toto', 'test' => 'foo']);
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
$I->seeResponseContains('data');


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// GET ALL
$I->sendGET('api/object/');
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
$I->seeResponseContains('data');


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// GET ONE
$I->sendGET('api/object/123456789');
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
$I->seeResponseContains('data');
$I->seeResponseContains('toto');
$I->seeResponseContains('foo');


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODIFY ONE
$I->sendPUT('api/object/123456789', ['name' => 'yolo', 'test' => 'foo']);
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
$I->seeResponseContains('data');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ASSERT GET ONE
$I->sendGET('api/object/123456789');
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
$I->seeResponseContains('data');
$I->seeResponseContains('yolo');
$I->seeResponseContains('foo');


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// DELETE ONE
$I->sendDELETE('api/object/123456789');
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
$I->seeResponseContains('data');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ASSERT DELETE ONE
$I->sendGET('api/object/123456789');
$I->seeResponseCodeIs(500);