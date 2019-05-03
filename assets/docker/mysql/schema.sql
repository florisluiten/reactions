CREATE DATABASE `reactions`;
CREATE USER 'reactionsweb'@'%' IDENTIFIED BY 'mysecret123';
GRANT ALL on reactions.* TO 'reactionsweb'@'%';
