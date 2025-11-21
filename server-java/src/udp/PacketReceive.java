package udp;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.InetAddress;

public class PacketReceive {
	DatagramSocket socket;
	DatagramPacket packet;
	InetAddress remote_addr;
	String data;

	public PacketReceive(int port) throws Exception {
		socket = new DatagramSocket(port);
	}

	public String receivePacket() throws IOException {
		String result = "";
		packet = new DatagramPacket(new byte[256], 256);
		socket.receive(packet);
		remote_addr = packet.getAddress();
		ByteArrayInputStream bin = new ByteArrayInputStream(packet.getData());
		for (int i = 0; i < packet.getLength(); i++) {
			int data = bin.read();
			if (data == -1)
				break;
			else
				result += ((char) data);
		}

		socket.close();
		return result;
	}
}
