<?php

/* Response */
// $result
define('SUCCESS', 'true');
define('FAIL', 'false');

// $message
define('MSG_RES_SUCCESS', '정상적으로 처리되었습니다.');
define('MSG_UNVALID_REQ', '올바른 요청이 아닙니다.');
define('MSG_SERVER_ERR', '시스템 장애입니다.');
define('MSG_LOGOUT_SUCCESS', '정상적으로 로그아웃 되었습니다. 로그인 페이지로 이동합니다.');
define('MSG_NOT_EXIST_USER', '해당 사용자가 존재하지 않습니다.');
define('MSG_NOT_MATCH_PWD', '비밀번호가 일치하지 않습니다.');
define('MSG_NOT_EXIST_RADIUS', '반경값 설정이 되어있지 않습니다.');
define('MSG_NOT_EXIST_COORD', '좌표 설정이 되어있지 않습니다.');
define('MSG_NOT_EXIST_BUS_STN', '해당 버스 정류소 정보가 존재하지 않습니다.');
define('MSG_TEMPORARY_ERR', '일시적인 에러입니다. 새로고침 후 다시 시도해주세요.');

/* COOKIE */
define('COOKIE_ID', 'REMEMBER_ID');
define('COOKIE_LIMIT', 60*60*24*10);