<?php
/**
 * teleport
 * Created: 08.12.15 10:27
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\rbac;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * SystemRole - contain all system roles
 */

class SystemRole
{
    const ADMINISTRATOR = 'administrator';
    const EMPLOYEE = 'employee';
    const OSKR = 'oskr';
    const RSO = 'rso';
    const UNLIMITED_BOOKING = 'unlimited_booking';
    const CONNECTION_INFO_ACCESS = 'connection_info_access';
    const WORK_WITH_REPORTS = 'work_with_reports';
}