<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 근무현황 관리 권한 설정
|--------------------------------------------------------------------------
|
| 근무현황 등록/수정/삭제/엑셀 다운로드 권한이 있는 아이디 목록
| 필요시 이 파일을 열어서 아이디를 추가/삭제하세요.
|
*/

$config['attendance_managers'] = array(
    'admin',        // 관리자     
    'lagerfeld',
    'dev',
    'wiseluxyong',
    'jhkim2232'

    // 와이즈럭스
    // 아래에 권한을 줄 아이디를 추가하세요
    // 'hong',
    // 'kim',
);

/* End of file attendance.php */
/* Location: ./application/config/attendance.php */
