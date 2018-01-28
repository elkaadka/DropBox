# DropBox Api Client

End points are added one at the time, if you need shoot me an email or make a pull request, i'll be glad to add it or check your PR.

##How it works

The client is the main DropBox API. It takes the access token as the only parameter

```
$client = new \Kanel\DropBox\Client('access_token');
```

##1. Upload

This call uploads a file from your disk to dropbox

If the file is <= 150Mb, it will be uploaded using this end point : https://www.dropbox.com/developers/documentation/http/documentation#files-upload

If the file is > 150Mb an upload session is automatically created using the following end points :
   
https://www.dropbox.com/developers/documentation/http/documentation#files-upload_session-start
https://www.dropbox.com/developers/documentation/http/documentation#files-upload_session-append_v2
https://www.dropbox.com/developers/documentation/http/documentation#files-upload_session-finish

### 1.1 Basic upload

```
$client = new Client('access_token');
$client->upload('/path/to/your/file', 'path/folder/dropbox');
```

Note that if the dropBox folder path is not specified, it defaults to / (root of your application folder)

### 1.2 Upload with parameters

Dropbox offers some upload parameters you might want to use when uploading a file.
You can use the UploadParameters class:

 ```
 $client = new Kanel\DropBox\Client('access_token');
 $uploadParameter = new \Kanel\DropBox\Parameters\UploadParameters();
 $uploadParameter->setAutoRenameFile(true);
 $client->upload('/path/to/your/file', 'path/folder/dropbox', $uploadParameter);
 ```
 
The parameters that can be edited from the parameters are all listed here : https://www.dropbox.com/developers/documentation/http/documentation#files-upload
You can check that class $uploadParameter for more info too

One of the most important parameter here is $chunksSize
This parameter allows you to change the size of chunks to upload when the file exceeds 150Mb
If the file is > 150Mb the file will be split in chunks of $chunksSize and ech chunk uploaded using sessions

 ```
 $client = new Kanel\DropBox\Client('access_token');
 $uploadParameter = new \Kanel\DropBox\Parameters\UploadParameters();
 $uploadParameter->setChunksSize(10485760);
 $client->upload('/path/to/your/file', 'path/folder/dropbox', $uploadParameter);
 ```
 
 In this example, if the file is > 150Mb, it will be split in 10Mb chunks and each chunk uploaded separately.
 