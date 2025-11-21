package monitor_system;

import org.hyperic.sigar.CpuPerc;
import org.hyperic.sigar.Sigar;
import org.hyperic.sigar.SigarLoader;
import org.hyperic.sigar.cmd.Shell;
import org.hyperic.sigar.cmd.SigarCommandBase;
import org.hyperic.sigar.SigarException;

/**
 * Display cpu information for each cpu found on the system.
 */
public class CPUInfo extends SigarCommandBase {

	public boolean displayTimes = true;
	org.hyperic.sigar.CpuInfo[] infos;
	CpuPerc[] cpus;

	public CPUInfo(Shell shell) {
		super(shell);
	}

	public CPUInfo() throws SigarException {
		super();
		infos = this.sigar.getCpuInfoList();
		cpus = this.sigar.getCpuPercList();

	}

	public String printCPUInfo() {
		String result = "{";
		org.hyperic.sigar.CpuInfo info = infos[0];
		long cacheSize = info.getCacheSize();
		result += "\"vendor\" : " + "\"" + info.getVendor() + "\",";
		result += "\"model\" : " + "\"" + info.getModel() + "\",";
		result += "\"mhz\" : " + "\"" + info.getMhz() + "\",";
		if (cacheSize != Sigar.FIELD_NOTIMPL) {
			result += "\"cache\" : " + "\"" + cacheSize + "\",";
		}

		if ((info.getTotalCores() != info.getTotalSockets()) || (info.getCoresPerSocket() > info.getTotalCores())) {
			result += "\"cores\" : " + "\"" + info.getTotalCores() + "\",";
			result += "\"physicalCPUs\" : " + "\"" + info.getTotalSockets() + "\",";
			result += "\"coreCPU\" : " + "\"" + info.getCoresPerSocket() + "\"";
		} else {
			result += "\"cores\" : " + "\"" + info.getTotalCores() + "\"";
		}
		result += "}";

		return result;
	}
	
	public String printCores(){
		String result = "["; 
		for (int i = 0; i < cpus.length; i++) {
			if(i == cpus.length-1){
				result += printCoreInformation(i);
			}else{
				result += printCoreInformation(i) + ", ";				
			}
		}
		result += "]";
		return result;		
	}

	public String printCoreInformation(int core) {
		CpuPerc cpu = cpus[core];
		String result = "{";

		result += "\"core\" : " + "\"" + core + "\",";
		result += "\"userTime\" : " + "\"" + CpuPerc.format(cpu.getUser()) + "\",";
		result += "\"sysTime\" : " + "\"" + CpuPerc.format(cpu.getSys()) + "\",";
		result += "\"idleTime\" : " + "\"" + CpuPerc.format(cpu.getIdle()) + "\",";
		result += "\"waitTime\" : " + "\"" + CpuPerc.format(cpu.getWait()) + "\",";
		result += "\"niceTime\" : " + "\"" + CpuPerc.format(cpu.getNice()) + "\",";
		result += "\"combined\" : " + "\"" + CpuPerc.format(cpu.getCombined()) + "\",";

		if (SigarLoader.IS_LINUX) {
			result += "\"irqTime\" : " + "\"" + CpuPerc.format(cpu.getIrq()) + "\",";
			result += "\"softIrqTime\" : " + "\"" + CpuPerc.format(cpu.getSoftIrq()) + "\",";
			result += "\"stolenTime\" : " + "\"" + CpuPerc.format(cpu.getStolen()) + "\"";
		} else {
			result += "\"irqTime\" : " + "\"" + CpuPerc.format(cpu.getIrq()) + "\"";
		}
		result += "}";
		return result;
	}

	@Override
	public void output(String[] arg0) throws SigarException {
		
	}

}
