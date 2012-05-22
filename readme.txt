Intercessor 1.0 Readme File
For CSC309 A2. Arnold Rosenbloom
------------------------

Menu
1. How to install
2. Protocol
3. Additional notes

How to install
---------------

just open login.php.
For permissions, all files get 644 and folders 755

Protocol
---------

For One-To-One Chatting:

We use a 2 message files system, one for each side the chat.

Client1(C1): addMessage.php?message=<message to be sent to Client2(C2)>&recepient=<C2>
C1: chat.php redisplays the message that was sent by C1.
Server: OK <if message successfully recieved and queued for client>
Server: FAILED <if message could not be queued for client>
Client2: getMessage.php?numToGet=<number of new messages to retrieve from the service>



Addtional notes
---------------

TO BE COMPLETED