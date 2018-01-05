<?php
// Common
if (!defined('STRING_REPLACE')) define('STRING_REPLACE', '%s');
if (!defined('QUANTITY_PER_PAGE')) define('QUANTITY_PER_PAGE', 20);
if (!defined('LIST_QUANTITY_PER_PAGE')) define('LIST_QUANTITY_PER_PAGE', serialize([5, 10, 20, 30]));

if (!defined('MESSAGE_STATUS_ERROR')) define('MESSAGE_STATUS_ERROR', 'alert-danger');
if (!defined('MESSAGE_STATUS_SUCCESS')) define('MESSAGE_STATUS_SUCCESS', 'alert-success');
if (!defined('CONFIRM_EMAIL_INACTIVE')) define('CONFIRM_EMAIL_INACTIVE', 0);
if (!defined('CONFIRM_EMAIL_ACTIVE')) define('CONFIRM_EMAIL_ACTIVE', 1);

if (!defined('ASC')) define('ASC', 'asc');
if (!defined('DESC')) define('DESC', 'desc');

//Active status
if (!defined('INACTIVE')) define('INACTIVE', 0);
if (!defined('ACTIVE')) define('ACTIVE', 1);

if (!defined('SERVICE_FEE_PERCENT')) define('SERVICE_FEE_PERCENT', 0.5);

//Log type
if (!defined('LOG_TYPE_DEBUG')) define('LOG_TYPE_DEBUG', 100);
if (!defined('LOG_TYPE_INFO')) define('LOG_TYPE_INFO', 200);
if (!defined('LOG_TYPE_NOTICE')) define('LOG_TYPE_NOTICE', 250);
if (!defined('LOG_TYPE_WARNING')) define('LOG_TYPE_WARNING', 300);
if (!defined('LOG_TYPE_ERROR')) define('LOG_TYPE_ERROR', 400);
if (!defined('LOG_TYPE_CRITICAL')) define('LOG_TYPE_CRITICAL', 500);
if (!defined('LOG_TYPE_ALERT')) define('LOG_TYPE_ALERT', 550);
if (!defined('LOG_TYPE_EMERGENCY')) define('LOG_TYPE_EMERGENCY', 600);