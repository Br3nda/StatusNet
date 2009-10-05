<?php
/**
 * StatusNet, the distributed open-source microblogging tool
 *
 * Subscribe to a user via the API
 *
 * PHP version 5
 *
 * LICENCE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  API
 * @package   StatusNet
 * @author    Zach Copley <zach@status.net>
 * @copyright 2009 StatusNet, Inc.
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link      http://status.net/
 */

if (!defined('STATUSNET')) {
    exit(1);
}

require_once INSTALLDIR.'/lib/apiauth.php';

/**
 * Allows the authenticating users to follow (subscribe) the user specified in
 * the ID parameter.  Returns the befriended user in the requested format when
 * successful.  Returns a string describing the failure condition when unsuccessful.
 *
 * @category API
 * @package  StatusNet
 * @author   Zach Copley <zach@status.net>
 * @license  http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link     http://status.net/
 */

class ApiFriendshipsCreateAction extends ApiAuthAction
{

    var $format = null;
    var $user   = null;
    var $other  = null;

    /**
     * Take arguments for running
     *
     * @param array $args $_REQUEST args
     *
     * @return boolean success flag
     *
     */

    function prepare($args)
    {
        parent::prepare($args);

        if ($this->requiresAuth()) {
            if ($this->checkBasicAuthUser() == false) {
                return;
            }
        }

        $this->format = $this->arg('format');
        $this->user   = $this->auth_user;
        $this->other  = $this->getTargetUser($id);

        return true;
    }

    /**
     * Handle the request
     *
     * Check the format and show the user info
     *
     * @param array $args $_REQUEST data (unused)
     *
     * @return void
     */

    function handle($args)
    {
        parent::handle($args);

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->clientError(
                _('This method requires a POST.'),
                400,
                $this->format
            );
            return;
        }

        if (empty($this->other)) {
            $this->clientError(
                _('Could not follow user: User not found.'),
                403,
                $this->format
            );
            return;
        }

        if ($this->user->isSubscribed($this->other)) {
            $errmsg = sprintf(
                _('Could not follow user: %s is already on your list.'),
                $this->other->nickname
            );
            $this->clientError($errmsg, 403, $this->format);
            return;
        }

        $result = subs_subscribe_to($this->user, $this->other);

        if (is_string($result)) {
            $this->clientError($result, 403, $this->format);
            return;
        }

        $this->init_document($this->format);
        $this->show_profile($this->other, $this->format);
        $this->end_document($this->format);
    }

}
