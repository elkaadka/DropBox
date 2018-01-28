<?php

namespace Kanel\DropBox\Responses;

class UploadResponse
{
    public $name;
    public $id;
    public $client_modified;
    public $server_modified;
    public $rev;
    public $size;
    public $path_lower;
    public $path_display;
    public $sharing_info;
    public $property_groups;
    public $has_explicit_shared_members;
    public $content_hash;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getClientModified()
    {
        return $this->client_modified;
    }

    public function setClientModified($client_modified)
    {
        $this->client_modified = $client_modified;
    }

    public function getServerModified()
    {
        return $this->server_modified;
    }

    public function setServerModified($server_modified)
    {
        $this->server_modified = $server_modified;
    }

    public function getRev()
    {
        return $this->rev;
    }

    public function setRev($rev)
    {
        $this->rev = $rev;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getPathLower()
    {
        return $this->path_lower;
    }

    public function setPathLower($path_lower)
    {
        $this->path_lower = $path_lower;
    }

    public function getPathDisplay()
    {
        return $this->path_display;
    }

    public function setPathDisplay($path_display)
    {
        $this->path_display = $path_display;
    }

    public function getSharingInfo()
    {
        return $this->sharing_info;
    }

    public function setSharingInfo($sharing_info)
    {
        $this->sharing_info = $sharing_info;
    }

    public function getPropertyGroups()
    {
        return $this->property_groups;
    }

    public function setPropertyGroups($property_groups)
    {
        $this->property_groups = $property_groups;
    }

    public function getHasExplicitSharedMembers()
    {
        return $this->has_explicit_shared_members;
    }

    public function setHasExplicitSharedMembers($has_explicit_shared_members)
    {
        $this->has_explicit_shared_members = $has_explicit_shared_members;
    }

    public function getContentHash()
    {
        return $this->content_hash;
    }

    public function setContentHash($content_hash)
    {
        $this->content_hash = $content_hash;
    }

}