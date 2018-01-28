<?php

namespace Kanel\DropBox\Parameters;


use Kanel\DropBox\Exceptions\DropBoxException;

class UploadParameters
{
    const WRITE_MODE_ADD = 'add';
    const WRITE_MODE_OVERWRITE = 'overwrite';
    const WRITE_MODE_UPDATE = 'update';

    protected $newName;
    protected $writeMode;
    protected $writeModeRev;
    protected $autoRenameFile = false;
    protected $mute = false;
    protected $clientModified;
    protected $propertyGroups;
    protected $chunksSize = 156237824; //149MO


    public function getNewName()
    {
        return $this->newName;
    }

    /**
     * The new name of the file in dropBox
     * Optional, if not set the file will have the same name as the source file
     */
    public function setNewName(string $newName)
    {
        $this->newName = $newName;
    }

    public function getWriteMode(): string
    {
        return $this->writeMode ?? self::WRITE_MODE_ADD;
    }

    /**
     * mode: Selects what to do if the file already exists. The default for this union is add.
     * Your intent when writing a file to some path. This is used to determine what constitutes a conflict and what the autorename strategy is.
     * In some situations, the conflict behavior is identical:
     * (a) If the target path doesn't refer to anything, the file is always written; no conflict.
     * (b) If the target path refers to a folder, it's always a conflict.
     * (c) If the target path refers to a file with identical contents, nothing gets written; no conflict.
     * The conflict checking differs in the case where there's a file at the target path with contents different from the contents you're trying to write.
     * The value will be one of the following datatypes:
     *
     * 1- UploadParameters::WRITE_MODE_ADD : Do not overwrite an existing file if there is a conflict.
     *    The autorename strategy is to append a number to the file name.
     *    For example, "document.txt" might become "document (2).txt".
     *
     * 2- UploadParameters::WRITE_MODE_OVERWRITE : Always overwrite the existing file. The autorename strategy is the same as it is for add.
     *
     * 3 - UploadParameters::WRITE_MODE_UPDATE : used with $rev:string(min_length=9, pattern="[0-9a-f]+") Overwrite if the given "rev" matches the existing file's "rev".
     *     The autorename strategy is to append the string "conflicted copy" to the file name.
     *     For example, "document.txt" might become "document (conflicted copy).txt" or "document (Panda's conflicted copy).txt".

     * @param string $writeMode
     * @param string $rev mandatory if $writeMode is WRITE_MODE_UPDATE
     * @throws DropBoxException
     */
    public function setWriteMode(string $writeMode, string $rev = null)
    {
        if (in_array($writeMode, [self::WRITE_MODE_ADD, self::WRITE_MODE_OVERWRITE, self::WRITE_MODE_UPDATE])) {
            $this->writeMode = $writeMode;
        }

        if ($writeMode === self::WRITE_MODE_UPDATE && is_null($rev)) {
            throw new DropBoxException('rev is mandatory in update mode');
        }

        $this->writeModeRev = $rev;
    }

    public function isAutoRenameFile(): bool
    {
        return $this->autoRenameFile;
    }

    /**
     *  If there's a conflict, as determined by mode, have the Dropbox server try to autorename the file to avoid conflict.
     *  The default for this field is False.
     */
    public function setAutoRenameFile(bool $autoRenameFile)
    {
        $this->autoRenameFile = $autoRenameFile;
    }

    public function isMute(): bool
    {
        return $this->mute;
    }

    /**
     * users are made aware of any file modifications in their Dropbox account via notifications in the client software.
     * If true, this tells the clients that this modification shouldn't result in a user notification. The default for this field is False.
     */
    public function setMute(bool $mute)
    {
        $this->mute = $mute;
    }

    public function getClientModified()
    {
        return $this->clientModified;
    }

    /**
     * @param $clientModified Timestamp(format="%Y-%m-%dT%H:%M:%SZ")?
     * The value to store as the client_modified timestamp.
     * Dropbox automatically records the time at which the file was written to the Dropbox servers.
     * It can also record an additional timestamp, provided by Dropbox desktop clients, mobile clients, and API apps of when the file was actually created or modified.
     * This field is optional.
     */
    public function setClientModified($clientModified)
    {
        $this->clientModified = $clientModified;
    }

    public function getPropertyGroups()
    {
        return $this->propertyGroups;
    }

    /**
     * List of custom properties to add to file.
     */
    public function setPropertyGroups(array $propertyGroups)
    {
        $this->propertyGroups = $propertyGroups;
    }

    public function getChunksSize(): int
    {
        return $this->chunksSize;
    }

    /**
     * Change the size of chunks to upload when the file exceeds 150Mb
     * If the file is < 150Mb it will be uploaded in one go using the upload endpoint
     * If the file is > 150Mb the file will be split in chunks of $chunksSize and ech chunk uploaded using sessions
     *
     * @param int $chunksSize in bytes. Must be a positive number  and < 150Mb
     * @throws DropBoxException
     */
    public function setChunksSize(int $chunksSize)
    {
        if ($chunksSize <= 0 || $chunksSize > 157286400) {
            throw new DropBoxException('chunk size must be > 0 and < 150Mb');
        }
        $this->chunksSize = $chunksSize;
    }


}
