package udp;

import java.io.ByteArrayOutputStream;
import java.io.PrintStream;
import java.net.DatagramPacket;
import java.net.DatagramSocket; // UDP
import java.net.InetAddress;

public class PacketSender {

	int port;
	String hostname;
	DatagramSocket socket;
	InetAddress remote_addr;

	public PacketSender(String hostname, int port) throws Exception {
		this.hostname = hostname;
		socket = new DatagramSocket();
		remote_addr = InetAddress.getByName(hostname);
		this.port = port;
	}

	public int getLocalPort() {
		return socket.getLocalPort();
	}

	public void sendMessage(String message) throws Exception {
		ByteArrayOutputStream bout = new ByteArrayOutputStream();
		PrintStream pout = new PrintStream(bout);
		pout.print(message);
		byte[] barray = bout.toByteArray();
		DatagramPacket packet = new DatagramPacket(barray, barray.length);
		packet.setAddress(remote_addr);
		packet.setPort(port);
		socket.send(packet);
	}

}
