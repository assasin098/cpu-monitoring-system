import java.io.IOException;

import tcp.TCPServer;

public class MonitoringSystemServer {

	static TCPServer server;

	public static void main(String[] args) throws Exception {
		server = new TCPServer(8999);
		server.listen();

		String data = server.getClient();

		while (!data.equals("QUIT")) {
			switch (data) {

			case "CHK":
				server.sendToClient("ACK");
				break;
				
			case "IP":
				server.sendToClient("ACK");
				
				break;
			}

		}

		if (data.equals("REG")) {
		}

	}

}
