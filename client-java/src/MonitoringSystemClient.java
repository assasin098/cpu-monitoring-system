import monitor_system.CPUInfo;
import monitor_system.Version;
import tcp.TCPServer;

public class MonitoringSystemClient {

	public static void main(String[] args) throws Exception {
		TCPServer tcpServer = new TCPServer(8999);
		tcpServer.listen();
		String data = tcpServer.getClient();
		Thread t;
		Sender sender = new Sender();

		while (true) {

			switch (data) {

			case "CHK":
				tcpServer.sendToClient("ACK");
				break;
			case "SYSINFO":
				tcpServer.sendToClient(new Version().printInfo());
				break;
			case "CPUINFO":
				tcpServer.sendToClient(new CPUInfo().printCPUInfo());
				break;
			case "PRINTCORES":
				tcpServer.sendToClient(new CPUInfo().printCores());
				break;
			case "PRINTCORE":
				tcpServer.sendToClient("ip");
				data = tcpServer.getClient();
				// sender is UDP data pusher connect to the IP tcp sender 
				// 9000 is port that is agreed upon
				t = new Thread(new Sender(data, 9000));
				t.start();
				tcpServer.in.close();
				break;

			case "STOP":
				tcpServer.sendToClient("ok");
				sender.isRun = false;
				break;
			}

			tcpServer.listen();
			data = tcpServer.getClient();
		}

	}
}
