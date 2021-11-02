<?php

namespace Fluxlabs\CQRS\Aggregate;

/**
 * Class RevisionFactory
 *
 * Generates Revision safe Revision id for IsRevisable object
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 */
class RevisionFactory
{
    const NAME_KEY = "revision_factory_revision_name_key";
    const NAME_SEPERATOR = "_";


    /**
     * Revisable object will be stamped with a valid RevisionId
     */
    public static function SetRevisionId(IsRevisable $entity, string $revision_name)
    {
        $entity->setRevisionId(self::GenerateRevisionId($entity, $revision_name));
    }


    /**
     * check if the RevisionId of an object and his data match, if not the object
     * is corrupt or has been tampered with
     */
    public static function ValidateRevision(IsRevisable $entity) : bool
    {
        $revision_id = $entity->getRevisionId();
        return $revision_id->GetKey() === GenerateRevisionKey($entity, $revision_id->getName(), $revision_id->getAlgorithm());
    }


    /**
     * Generates the key by hashing the revision data and adds the hash of the
     * data containing the name with the name which should make it impossible
     * to create objects that have the same key that do not contain the same data
     */
    private static function GenerateRevisionId(IsRevisable $entity, string $revision_name, string $algorithm = 'sha512') : RevisionId
    {
        return RevisionId::create(self::GenerateRevisionKey($entity, $revision_name, $algorithm), $algorithm, $revision_name);
    }
    
    private static function GenerateRevisionKey(IsRevisable $entity, string $revision_name, string $algorithm) : string
    {
        $data = serialize($entity);
        $data .= $revision_name;
        
        return hash($algorithm, $data);
    }
}
