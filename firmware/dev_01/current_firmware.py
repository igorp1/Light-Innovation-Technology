from socket import *
from Servo import *
import time
serverSocket = socket(AF_INET, SOCK_STREAM)

#Prepare a server socket
host = '168.122.8.209'
port = 10001
backlog = 5
serverSocket.setsockopt(SOL_SOCKET, SO_REUSEADDR,1)
serverSocket.bind((host, port))
size = 1024

serverSocket.listen(backlog)

# edison servo setup
# Create a new servo object with a reference name
myServo = Servo("First Servo")

# Attaches the servo to pin 3 in Arduino Expansion board
myServo.attach(3)

# Print servo settings
print ""
print "*** Servo Initial Settings ***"
print myServo
print ""


while True:
    #Establish the connection
    print 'Ready to serve...'
    connectionSocket, addr = serverSocket.accept()
    try:
        message = connectionSocket.recv(size)
        filename = message.split()[1]
        f = open(filename[1:])
        # Read file
        outputdata = f.readlines()
        #Send HTTP status line into socket
        connectionSocket.send('HTTP/1.0 200 OK')
        #Send Last-Modified HTTP header line into socket
        #For simplicity, it is OK to hard code the datfe
        connectionSocket.send('Last-Modified: ' + time.strftime("%c") + "\n\n")
        #Send the content of the requested file to the client
        for i in range(0, len(outputdata)):
            connectionSocket.send(outputdata[i])
        connectionSocket.send("\r\n")
        #Close client socket
        connectionSocket.close()
    except IOError:
        #Send response message for file not found
        connectionSocket.send('Moving servo...')
        ### RUN SERVO STUFF
        for x in range(0, 10):
            # From 0 to 180 degrees
            for angle in range(0,180):
                myServo.write(angle)
                time.sleep(0.005)

            # From 180 to 0 degrees
            for angle in range(180,-1,-1):
                myServo.write(angle)
                time.sleep(0.005)
            ###

        connectionSocket.send('Done')
        #Close client socket
        connectionSocket.close()
serverSocket.close()
