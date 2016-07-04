#!/usr/bin/env python
#-*- encoding: utf-8 -*-

import sys, json
from bs4 import BeautifulSoup

PERMISOS =  {
	'ACCESS_CHECKIN_PROPERTIES': 'Allows read/write access to the "properties" table in the checkin database, to change values that get uploaded.',
	'ACCESS_COARSE_LOCATION': 'Allows an app to access approximate location.',
	'ACCESS_FINE_LOCATION': 'Allows an app to access precise location.',
	'ACCESS_LOCATION_EXTRA_COMMANDS': 'Allows an application to access extra location provider commands.',
	'ACCESS_NETWORK_STATE': 'Allows applications to access information about networks.',
	'ACCESS_NOTIFICATION_POLICY': 'Marker permission for applications that wish to access notification policy.',
	'ACCESS_WIFI_STATE': 'Allows applications to access information about Wi-Fi networks.',
	'ACCOUNT_MANAGER': 'Allows applications to call into AccountAuthenticators.',
	'ADD_VOICEMAIL': 'Allows an application to add voicemails into the system.',
	'BATTERY_STATS': 'Allows an application to collect battery statistics',
	'BIND_ACCESSIBILITY_SERVICE': 'Must be required by an AccessibilityService, to ensure that only the system can bind to it.',
	'BIND_APPWIDGET': 'Allows an application to tell the AppWidget service which application can access AppWidget\'s data.',
	'BIND_CARRIER_MESSAGING_SERVICE': 'This constant was deprecated in API level 23. Use BIND_CARRIER_SERVICES instead',
	'BIND_CARRIER_SERVICES': 'The system process that is allowed to bind to services in carrier apps will have this permission.',
	'BIND_CHOOSER_TARGET_SERVICE': 'Must be required by a ChooserTargetService, to ensure that only the system can bind to it.',
	'BIND_DEVICE_ADMIN': 'Must be required by device administration receiver, to ensure that only the system can interact with it.',
	'BIND_DREAM_SERVICE': 'Must be required by an DreamService, to ensure that only the system can bind to it.',
	'BIND_INCALL_SERVICE': 'Must be required by a InCallService, to ensure that only the system can bind to it.',
	'BIND_INPUT_METHOD': 'Must be required by an InputMethodService, to ensure that only the system can bind to it.',
	'BIND_MIDI_DEVICE_SERVICE': 'Must be required by an MidiDeviceService, to ensure that only the system can bind to it.',
	'BIND_NFC_SERVICE': 'Must be required by a HostApduService or OffHostApduService to ensure that only the system can bind to it.',
	'BIND_NOTIFICATION_LISTENER_SERVICE': 'Must be required by an NotificationListenerService, to ensure that only the system can bind to it.',
	'BIND_PRINT_SERVICE': 'Must be required by a PrintService, to ensure that only the system can bind to it.',
	'BIND_REMOTEVIEWS': 'Must be required by a RemoteViewsService, to ensure that only the system can bind to it.',
	'BIND_TELECOM_CONNECTION_SERVICE': 'Must be required by a ConnectionService, to ensure that only the system can bind to it.',
	'BIND_TEXT_SERVICE': 'Must be required by a TextService (eg SpellCheckerService) to ensure that only the system can bind to it.',
	'BIND_TV_INPUT': 'Must be required by a TvInputService to ensure that only the system can bind to it.',
	'BIND_VOICE_INTERACTION': 'Must be required by a VoiceInteractionService, to ensure that only the system can bind to it.',
	'BIND_VPN_SERVICE': 'Must be required by a VpnService, to ensure that only the system can bind to it.',
	'BIND_WALLPAPER': 'Must be required by a WallpaperService, to ensure that only the system can bind to it.',
	'BLUETOOTH': 'Allows applications to connect to paired bluetooth devices.',
	'BLUETOOTH_ADMIN': 'Allows applications to discover and pair bluetooth devices.',
	'BLUETOOTH_PRIVILEGED': 'Allows applications to pair bluetooth devices without user interaction, and to allow or disallow phonebook access or message access.',
	'BODY_SENSORS': 'Allows an application to access data from sensors that the user uses to measure what is happening inside his/her body, such as heart rate.',
	'BROADCAST_PACKAGE_REMOVED': 'Allows an application to broadcast a notification that an application package has been removed.',
	'BROADCAST_SMS': 'Allows an application to broadcast an SMS receipt notification.',
	'BROADCAST_STICKY': 'Allows an application to broadcast sticky intents.',
	'BROADCAST_WAP_PUSH': 'Allows an application to broadcast a WAP PUSH receipt notification.',
	'CALL_PHONE': 'Allows an application to initiate a phone call without going through the Dialer user interface for the user to confirm the call.',
	'CALL_PRIVILEGED': 'Allows an application to call any phone number, including emergency numbers, without going through the Dialer user interface for the user to confirm the call being placed.',
	'CAMERA': 'Required to be able to access the camera device.',
	'CAPTURE_AUDIO_OUTPUT': 'Allows an application to capture audio output.',
	'CAPTURE_SECURE_VIDEO_OUTPUT': 'Allows an application to capture secure video output.',
	'CAPTURE_VIDEO_OUTPUT': 'Allows an application to capture video output.',
	'CHANGE_COMPONENT_ENABLED_STATE': 'Allows an application to change whether an application component (other than its own) is enabled or not.',
	'CHANGE_CONFIGURATION': 'Allows an application to modify the current configuration, such as locale.',
	'CHANGE_NETWORK_STATE': 'Allows applications to change network connectivity state.',
	'CHANGE_WIFI_MULTICAST_STATE': 'Allows applications to enter Wi-Fi Multicast mode.',
	'CHANGE_WIFI_STATE': 'Allows applications to change Wi-Fi connectivity state.',
	'CLEAR_APP_CACHE': 'Allows an application to clear the caches of all installed applications on the device.',
	'CONTROL_LOCATION_UPDATES': 'Allows enabling/disabling location update notifications from the radio.',
	'DELETE_CACHE_FILES': 'Allows an application to delete cache files.',
	'DELETE_PACKAGES': 'Allows an application to delete packages.',
	'DIAGNOSTIC': 'Allows applications to RW to diagnostic resources.',
	'DISABLE_KEYGUARD': 'Allows applications to disable the keyguard if it is not secure.',
	'DUMP': 'Allows an application to retrieve state dump information from system services.',
	'EXPAND_STATUS_BAR': 'Allows an application to expand or collapse the status bar.',
	'FACTORY_TEST': 'Run as a manufacturer test application, running as the root user.',
	'FLASHLIGHT': 'Allows access to the flashlight.',
	'GET_ACCOUNTS': 'Allows access to the list of accounts in the Accounts Service.',
	'GET_ACCOUNTS_PRIVILEGED': 'Allows access to the list of accounts in the Accounts Service.',
	'GET_PACKAGE_SIZE': 'Allows an application to find out the space used by any package.',
	'GET_TASKS': 'This constant was deprecated in API level 21. No longer enforced.',
	'GLOBAL_SEARCH': 'This permission can be used on content providers to allow the global search system to access their data.',
	'INSTALL_LOCATION_PROVIDER': 'Allows an application to install a location provider into the Location Manager.',
	'INSTALL_PACKAGES': 'Allows an application to install packages.',
	'INSTALL_SHORTCUT': 'Allows an application to install a shortcut in Launcher.',
	'INTERNET': 'Allows applications to open network sockets.',
	'KILL_BACKGROUND_PROCESSES': 'Allows an application to call killBackgroundProcesses(String).',
	'LOCATION_HARDWARE': 'Allows an application to use location features in hardware, such as the geofencing api.',
	'MANAGE_DOCUMENTS': 'Allows an application to manage access to documents, usually as part of a document picker.',
	'MASTER_CLEAR': 'Not for use by third-party applications.',
	'MEDIA_CONTENT_CONTROL': 'Allows an application to know what content is playing and control its playback.',
	'MODIFY_AUDIO_SETTINGS': 'Allows an application to modify global audio settings.',
	'MODIFY_PHONE_STATE': 'Allows modification of the telephony state - power on, mmi, etc.',
	'MOUNT_FORMAT_FILESYSTEMS': 'Allows formatting file systems for removable storage.',
	'MOUNT_UNMOUNT_FILESYSTEMS': 'Allows mounting and unmounting file systems for removable storage.',
	'NFC': 'Allows applications to perform I/O operations over NFC.',
	'PACKAGE_USAGE_STATS': 'Allows an application to collect component usage statistics. Declaring the permission implies intention to use the API and the user of the device can grant permission through the Settings application.',
	'PERSISTENT_ACTIVITY': 'This constant was deprecated in API level 9. This functionality will be removed in the future; please do not use. Allow an application to make its activities persistent.',
	'PROCESS_OUTGOING_CALLS': 'Allows an application to see the number being dialed during an outgoing call with the option to redirect the call to a different number or abort the call altogether.',
	'READ_CALENDAR': 'Allows an application to read the user\'s calendar data.',
	'READ_CALL_LOG': 'Allows an application to read the user\'s call log.',
	'READ_CONTACTS': 'Allows an application to read the user\'s contacts data.',
	'READ_EXTERNAL_STORAGE': 'Allows an application to read from external storage.',
	'READ_FRAME_BUFFER': 'Allows an application to take screen shots and more generally get access to the frame buffer data.',
	'READ_INPUT_STATE': 'This constant was deprecated in API level 16. The API that used this permission has been removed.',
	'READ_LOGS': 'Allows an application to read the low-level system log files.',
	'READ_PHONE_STATE': 'Allows read only access to phone state.',
	'READ_SMS': 'Allows an application to read SMS messages.',
	'READ_SYNC_SETTINGS': 'Allows applications to read the sync settings.',
	'READ_SYNC_STATS': 'Allows applications to read the sync stats.',
	'READ_VOICEMAIL': 'Allows an application to read voicemails in the system.',
	'REBOOT': 'Required to be able to reboot the device.',
	'RECEIVE_BOOT_COMPLETED': 'Allows an application to receive the ACTION_BOOT_COMPLETED that is broadcast after the system finishes booting.',
	'RECEIVE_MMS': 'Allows an application to monitor incoming MMS messages.',
	'RECEIVE_SMS': 'Allows an application to receive SMS messages.',
	'RECEIVE_WAP_PUSH': 'Allows an application to receive WAP push messages.',
	'RECORD_AUDIO': 'Allows an application to record audio.',
	'REORDER_TASKS': 'Allows an application to change the Z-order of tasks.',
	'REQUEST_IGNORE_BATTERY_OPTIMIZATIONS': 'Permission an application must hold in order to use ACTION_REQUEST_IGNORE_BATTERY_OPTIMIZATIONS.',
	'REQUEST_INSTALL_PACKAGES': 'Allows an application to request installing packages.',
	'RESTART_PACKAGES': 'This constant was deprecated in API level 8. The restartPackage(String) API is no longer supported.',
	'SEND_RESPOND_VIA_MESSAGE': 'Allows an application (Phone) to send a request to other applications to handle the respond-via-message action during incoming calls.',
	'SEND_SMS': 'Allows an application to send SMS messages.',
	'SET_ALARM': 'Allows an application to broadcast an Intent to set an alarm for the user.',
	'SET_ALWAYS_FINISH': 'Allows an application to control whether activities are immediately finished when put in the background.',
	'SET_ANIMATION_SCALE': 'Modify the global animation scaling factor.',
	'SET_DEBUG_APP': 'Configure an application for debugging.',
	'SET_PREFERRED_APPLICATIONS': 'This constant was deprecated in API level 7. No longer useful, see addPackageToPreferred(String) for details.',
	'SET_PROCESS_LIMIT': 'Allows an application to set the maximum number of (not needed) application processes that can be running.',
	'SET_TIME': 'Allows applications to set the system time.',
	'SET_TIME_ZONE': 'Allows applications to set the system time zone.',
	'SET_WALLPAPER': 'Allows applications to set the wallpaper.',
	'SET_WALLPAPER_HINTS': 'Allows applications to set the wallpaper hints.',
	'SIGNAL_PERSISTENT_PROCESSES': 'Allow an application to request that a signal be sent to all persistent processes.',
	'STATUS_BAR': 'Allows an application to open, close, or disable the status bar and its icons.',
	'SYSTEM_ALERT_WINDOW': 'Allows an app to create windows using the type TYPE_SYSTEM_ALERT, shown on top of all other apps.',
	'TRANSMIT_IR': 'Allows using the device\'s IR transmitter, if available.',
	'UNINSTALL_SHORTCUT': 'Allows an application to uninstall a shortcut in Launcher.',
	'UPDATE_DEVICE_STATS': 'Allows an application to update device statistics.',
	'USE_FINGERPRINT': 'Allows an app to use fingerprint hardware.',
	'USE_SIP': 'Allows an application to use SIP service.',
	'VIBRATE': 'Allows access to the vibrator.',
	'WAKE_LOCK': 'Allows using PowerManager WakeLocks to keep processor from sleeping or screen from dimming.',
	'WRITE_APN_SETTINGS': 'Allows applications to write the apn settings.',
	'WRITE_CALENDAR': 'Allows an application to write the user\'s calendar data.',
	'WRITE_CALL_LOG': 'Allows an application to write (but not read) the user\'s contacts data.',
	'WRITE_CONTACTS': 'Allows an application to write the user\'s contacts data.',
	'WRITE_EXTERNAL_STORAGE': 'Allows an application to write to external storage.',
	'WRITE_GSERVICES': 'Allows an application to modify the Google service map.',
	'WRITE_SECURE_SETTINGS': 'Allows an application to read or write the secure system settings.',
	'WRITE_SETTINGS': 'Allows an application to read or write the system settings.',
	'WRITE_SYNC_SETTINGS': 'Allows applications to write the sync settings.',
	'WRITE_VOICEMAIL': 'Allows an application to modify and remove existing voicemails in the system. '
}

def getPermisos(content):
	perm = []
	bs = BeautifulSoup(content)
	for item in bs.find_all('uses-permission'):
		perm.append(item["android:name"])
	return perm

def parse(permisos):
	result = {}
	other = []
	for p in permisos:
		if "android.permission." in p:
			base = p.replace("android.permission.","")
			if base in PERMISOS.keys():
				result[p] = PERMISOS[base]
			else:
				other.append(p)
		else:
			other.append(p)
	result["OTHER"] = other
	return result


if __name__ == "__main__":

	if len(sys.argv) <= 1:
		sys.exit(-1)
	
	

	fichero = sys.argv[1]
	# C:\xampp\htdocs\tfg\uploads\88648f3d248335df5e864e87533190c3cdaac10f\AndroidManifest.xml
	f = open(fichero, 'r')
	content = f.read()
	f.close()

	perm = getPermisos(content)
	print json.dumps(parse(perm))