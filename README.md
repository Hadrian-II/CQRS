# CQRS
Contains the basic structure for an event sourced Data storage:
    
  * Aggregates
  * ValueObjects
  * Commands
  * CommandHandlers
  * Events
  * EventSourced Aggregates / EventStores
  * Projections
  * Revisions

## Getting Started

Basically in an event sourced environment, object do not store their current values, but all events that happen are appended to the database.

### AggregateRoot

An aggregate root is a main object in an event sourced environment, it is always initiated by an AggregateCreatedEvent and then all state changes are processed by the events that happen in the Environment

If you use the provided AbstractAggregateRoot all the Boilerplate things are already created and you can process event when you call the ExecuteEvent method with a DomainEvent object, the Event is then added to the internal Event que and changes can be processed in the apply*EventClass* Method

### AggregateRepository

The aggregate repository is a Repository class able to handle the Database operations belonging to an aggregate root.
To store an aggregate root, just use the save method in the AbstractAggregateRepository and all the not yet saved events are stored to the Database.
With getAggregateRootById you can load an aggregate root from the database. The Repository is automatically caching the aggregate.

## Data Structure

### Events

Event sourced Databases are une table per Aggregate root, and different Events are serialized/deserialized to JSON, it is by nature a NO SQL pattern, so its relational representation is not relationally normalized

All events have the following Structure

 * ID (Id of the event itself)
 * AggregateId (Id of the aggregate it belongs to)
 * EventName (Class of event, used for deserialization)
 * OccuredOn (Time of event)
 * InitiatingUserId (Event executing user)
 * EventBody (Event Data)

If you used the Provided AbstractDomainEvent, you just need to implement the getEventbody and restoreEventBody methods to provide serialization

### Value Objects

As event sourcing is an append only storage type, usually data is handeled in value object. A value object is just a data class which contains just the data and is immutable.

If you use the provided AbstractValueObject serialization/deserialization is handled automatically.

Best practices are that you create an create() method to generate a new object, and only implement getters for the contained values. It must be possible to call the constructor without any empty parameters, but due to the ILIAS technical bord being cargo culting dogmatics it could not be set to protected as static factory methods are "not object oriented" which is especially bad as PHP does not know constructor overloading.
Also all internal fields need to be set to protected, so that the serialization implemented in the Abstract class can see them.

### Revisions

Basically a revision is a fixed snapshot of an aggregate, if you implement the provided IsRevisable iterface and the Revision Factory, you can generate revisions which basically are events where the data of the aggregate is hashed and stored, so that you can check if the revision is still valid.
To load a revision either just apply all events until the revision event or better create a projection whith the data of the revision.

## Commands

Commands are a pattern that is used to process actions in a CQRS context, basically you throy a command at a commandbus and then the corresponding handler executes the command, the implementation here is anaemic as in practice it is just an additional layer of indirection which increases the complexity of the application without providing benefits. (The structure should not be part of a plugin but the basic way of an application)

## Projection

The projection stuff is used to automatically create projections, but i have neither written nor used it, so i cant say anything about it.