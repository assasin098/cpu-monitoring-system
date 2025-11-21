package tcp;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.Arrays;

public class TCPServer {

	int portNumber;
	ServerSocket server = null;
	Socket client = null;
	public InputStream in;
	OutputStream out;
	PrintWriter w;

	public TCPServer(int portnumber) throws Exception {
		server = new ServerSocket(portnumber);
		// server.setSoTimeout(99999);
		this.portNumber = portnumber;
	}
	
	public void listen() throws IOException{
		client = server.accept();
		in = client.getInputStream();
		out = client.getOutputStream();
		
	}

	public String getClient() throws Exception {
		// open();
		byte[] receiveBuf = new byte[512];
		Arrays.fill(receiveBuf, (byte) 0);

		
		
		in.read(receiveBuf);
//		// in.close();
		String result = new String(receiveBuf);
		result = result.trim();
//		result = result.replaceAll("\\r", "");
//		result = result.replaceAll("\\n", "");
//		result = result.replaceAll("\"", "");
		return result;
	}

	public void sendToClient(String message) throws Exception {
	
		w = new PrintWriter(client.getOutputStream(), true);
		w.println(message);
		w.flush();
	}

	public void open() throws IOException {
		server = new ServerSocket(portNumber);
	}

	public void close() throws Exception {
		out.close();
		in.close();
		// client.close();
		server.close();
	}
}
