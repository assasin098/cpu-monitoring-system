import monitor_system.CPUInfo;
import udp.PacketSender;

public class Sender implements Runnable {
	PacketSender sender;
	public boolean isRun = true;
	
	public Sender(){
		
	}
	
	public Sender(String hostname, int port) throws Exception {
		sender = new PacketSender(hostname, 9000);
		
	}

	@Override
	public void run() {
		while(isRun){
			try {
				Thread.sleep(2000); //send every 2 seconds
				sender.sendMessage(new CPUInfo().printCores());
			} catch (Exception e) {
				
			}
		}
	}
	
	

}
