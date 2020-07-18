<?php
/**
 * JnxVpnIfUp.php
 *
 * -Description-
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    LibreNMS
 * @link       http://librenms.org
 * @copyright  2018 KanREN, Inc.
 * @author     Neil Kahle <nkahle@kanren.net>
 */

namespace LibreNMS\Snmptrap\Handlers;

use App\Models\Device;
use LibreNMS\Interfaces\SnmptrapHandler;
use LibreNMS\Snmptrap\Trap;
use Log;

class JnxVpnIfUp implements SnmptrapHandler
{
    /**
     * Handle snmptrap.
     * Data is pre-parsed and delivered as a Trap.
     *
     * @param Device $device
     * @param Trap $trap
     * @return void
     */
    public function handle(Device $device, Trap $trap)
    {
        $vpnType = $trap->getOidData($trap->findOid('JUNIPER-VPN-MIB::jnxVpnIfVpnType'));
        $vpnName = $trap->getOidData($trap->findOid('JUNIPER-VPN-MIB::jnxVpnIfVpnName'));

        if (substr($vpnName, 0, 6) === "vt/lsi") {
            $vpnDevice = substr($vpnName, 7, 15);
            Log::event("$vpnType to device $vpnDevice is now connected", $device->device_id, 'trap', 1);
        } else {
            Log::event("$vpnType on interface $vpnName is now connected", $device->device_id, 'trap', 1);
        }
    }
}
