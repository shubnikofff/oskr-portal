<?php
/**
 * teleport
 * Created: 08.12.15 10:25
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\rbac;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * SystemPermission - contain all system permissions
 */

class SystemPermission
{
    const ADMIN_LOGIN = 'admin_login';
    const CREATE_REQUEST = 'create_request';
    const UPDATE_REQUEST = 'update_request';
    const UPDATE_OWN_REQUEST = 'update_own_request';
    const CANCEL_REQUEST = 'cancel_request';
    const CANCEL_OWN_REQUEST = 'cancel_own_request';
    const APPROVE_REQUEST = 'approve_request';
    const DELETE_REQUEST = 'delete_request';
    const RSO_AGREE = 'rso_agree';
    const RSO_REFUSE = 'rso_refuse';
    const BOOK_FOR_THE_YEAR = 'book_for_the_year';
}