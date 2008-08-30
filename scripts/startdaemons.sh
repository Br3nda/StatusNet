#!/bin/sh

# Laconica - a distributed open-source microblogging tool
# Copyright (C) 2008, Controlez-Vous, Inc.
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

# This program tries to start the daemons for Laconica. Note that the 'maildaemon' needs to run as a mail filter.

export INSTALLDIR=$1

/sbin/start-stop-daemon -S --exec $INSTALLDIR/scripts/xmppdaemon.php -b -m --pidfile=/var/run/xmppdaemon.pid
/sbin/start-stop-daemon -S --exec $INSTALLDIR/scripts/xmppqueuehandler.php -b -m --pidfile=/var/run/xmppqueuehandler.pid
/sbin/start-stop-daemon -S --exec $INSTALLDIR/scripts/publicqueuehandler.php -b -m --pidfile=/var/run/publicqueuehandler.pid
/sbin/start-stop-daemon -S --exec $INSTALLDIR/scripts/xmppconfirmhandler.php -b -m --pidfile=/var/run/xmppconfirmhandler.pid
/sbin/start-stop-daemon -S --exec $INSTALLDIR/scripts/smsqueuehandler.php -b -m --pidfile=/var/run/smsqueuehandler.pid
/sbin/start-stop-daemon -S --exec $INSTALLDIR/scripts/ombqueuehandler.php -b -m --pidfile=/var/run/ombqueuehandler.pid