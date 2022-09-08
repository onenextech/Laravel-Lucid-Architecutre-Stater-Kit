### File Attachment to Model
HasAttachable trait and File model are used to attach file to model. You can use it like this.

```php
// Registing the attachments in model
<?php namespace App\Data\Models;

use App\Data\Models\File;
use App\Traits\HasAttachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory , HasAttachable;

    protected $attachOne = [
        'avatar' => File::class
    ];

    protected $attachMany = [
        'photos' => File::class,
    ];
}

```

```php
// Attaching the file to model
use App\Data\Models\File;
use App\Data\Models\User;

$user = User::find(1);

// You can Use one of the following methods to attach the file to model
// --------------------------------------------------------------------

// Create file from Url
$avatar = new File();
$avatar->fromUrl('https://example.com/avatar.png');

// or Create file from UploadedFile
$cv = new File();
$uploadedFile = $request->file('resume');
$cv->fromPost($uploadedFile);
// ----------------------------------------------------------------------

// set the file as avatar
$user->avatar = $avatar;

// or set the files as photos
$user->documents = [$file, $cv];

$user->save();

```


Geting data from attached file

```php
// Get the file from model
$user = User::find(1);
$avatar = $user->avatar;

// Get the file url
$avatar->url;

// Get the file path
$avatar->path;

// Get the original file name
$avatar->name;

// Get the file extension
$avatar->extension;

// Get the file mime type
$avatar->mime_type;

// Get the file size
$avatar->size;

// Create a thumbnail of the file and get the path
$avatar->getThumb($width, $height);

// Create a thumbnail of the file with custimze options and get the url
$avatar->getThumb($width, $height, $options);

// Create a thumbnail of the file and get the url
$avatar->getThumbUrl($width, $height, $options);

// Create a thumbnail of the file with custimze options and get the url
$avatar->getThumbUrl($width, $height, $options);

/**
 * getThumb method
 *
 * @param  int  $width
 * @param  int  $height
 * @param  array  $options [
 *     'mode' => 'auto',        // Either exact, portrait, landscape, auto, fit or crop.
 *     'offset' => [0, 0],      // The offset of the crop = [ left, top ]
 *     'quality' => 90,         // Image quality, from 0 - 100 (default: 90)
 *     'sharpen' => 0,          // Sharpen image, from 0 - 100 (default: 0)
 *     'interlace' => false,    // Interlace image,  Boolean: false (disabled: default), true (enabled)
 *     'extension' => 'auto',   // Image extension, either auto or a valid extension
 * ]
 * @return string The URL to the generated thumbnail
 */
```

