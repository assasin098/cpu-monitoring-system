package monitor_system;

/*
 * Copyright (c) 2006-2009 Hyperic, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

//package org.hyperic.sigar.cmd;

import java.io.File;
import java.net.InetAddress;
import java.net.UnknownHostException;

import org.hyperic.sigar.OperatingSystem;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarException;
import org.hyperic.sigar.SigarLoader;
import org.hyperic.sigar.cmd.Shell;
import org.hyperic.sigar.cmd.SigarCommandBase;
import org.hyperic.sigar.win32.LocaleInfo;

/**
 * Display Sigar, java and system version information.
 */
public class Version extends SigarCommandBase {

	String JSONdata = "";

	public Version(Shell shell) {
		super(shell);
	}

	public Version() {
		super();
	}

	public String getUsageShort() {
		return "Display sigar and system version info";
	}

	private static String getHostName() {
		try {
			return InetAddress.getLocalHost().getHostName();
		} catch (UnknownHostException e) {
			return "unknown";
		}
	}

	private static String printNativeInfo() {

		String archlib = SigarLoader.getNativeLibraryName();
		String result = "{";
		String host = getHostName();
		String fqdn = "unknown";
		Sigar sigar = new Sigar();
		try {
			File lib = sigar.getNativeLibrary();
			if (lib != null) {
				archlib = lib.getName();
			}
			fqdn = sigar.getFQDN();
		} catch (SigarException e) {
			fqdn = "unknown";
		} finally {
			sigar.close();
		}
		result += "\"archlib\" : \"" + archlib + "\", ";

		if (SigarLoader.IS_WIN32) {
			LocaleInfo info = new LocaleInfo();
			result += "\"language\" : \"" + info + "\", ";
			result += "\"langID\" : \"" + info.getPerflibLangId() + "\", ";
		}

		if (!fqdn.equals(host)) {
			result += "\"fqdn\" : \"" + fqdn + "\", ";
			result += "\"hostname\" : \"" + host + "\"";
		} else {
			result += "\"fqdn\" : \"" + fqdn + "\"";
		}
		result += "}";

		return result;
	}

	public String printInfo() {

		String result = "{";

		try {
			result += "\"Native\" : " + printNativeInfo() + " ,";
		} catch (UnsatisfiedLinkError e) {

		}

		result += "\"user\":" + "\"" + System.getProperty("user.name") + "\", ";
		OperatingSystem sys = OperatingSystem.getInstance();
		result += "\"desc\":" + "\"" + sys.getDescription() + "\", ";
		result += "\"name\":" + "\"" + sys.getName() + "\", ";
		result += "\"arch\":" + "\"" + sys.getArch() + "\", ";
		result += "\"machine\":" + "\"" + sys.getMachine() + "\", ";
		result += "\"version\":" + "\"" + sys.getVersion() + "\", ";
		result += "\"patch\":" + "\"" + sys.getPatchLevel() + "\", ";
		result += "\"vendor\":" + "\"" + sys.getVendor() + "\", ";
//		result += "\"OS vendor version\":" + "\"" + sys.getVendorVersion() + "\", ";
		if (sys.getVendorCodeName() != null) {
			result += "\"codeName\":" + "\"" + sys.getVendorCodeName() + "\", ";
		}

		result += "\"dataModel\":" + "\"" + sys.getDataModel() + "\", ";
		result += "\"cpuEndian\":" + "\"" + sys.getCpuEndian() + "\", ";
		
		result += "\"javaVersion\":" + "\"" + System.getProperty("java.vm.version") + "\", ";
		
		result += "\"javaVendor\":" + "\"" + System.getProperty("java.vm.vendor") + "\"}";
		return result;

		
	}

	public void output(String[] args) {
//		printInfo();
	}

	
	

}
