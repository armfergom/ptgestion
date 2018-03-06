Table of Contents
=================

- [Game Actions](#game-actions)
  - [generic:spin](#genericspin)
- [Game Events](#game-events)
  - [generic:availableActions](#genericavailableactions)
  - [generic:cascadesEnd](#genericcascadesend)
  - [generic:cascadesProgress](#genericcascadesprogress)
  - [generic:cascadesStart](#genericcascadesstart)
  - [generic:featureTriggeredWinnings](#genericfeaturetriggeredwinnings)
  - [generic:spin](#genericspin)
  - [generic:symbolMovement](#genericsymbolmovement)
  - [generic:winnings](#genericwinnings)
- [Custom Types](#custom-types)
  - [CascadeState](#cascadestate)
  - [FreeSpinState](#freespinstate)
  - [MainState](#mainstate)
  - [PickAwardWrapper](#pickawardwrapper)
  - [PickerState](#pickerstate)
  - [PickAward](#pickaward)


Game Actions
============

This section describes the actions that can be sent to the server and some examples.

generic:spin
------------

The message to send to the server to perform a spin of the panel.

### Fields

| Name      | Type | Description                             |
| --------- | ---- | --------------------------------------- |
| betAmount | long | The panel bet to perform the spin with. |

#### Example

```
{"name":"generic:spin","gameSessionId":"","action":"{\"betAmount\":100}","type":"game-action"}
```

Game Events
===========

This section describes the events the server is able to produce and some examples.

generic:availableActions
------------------------

Message sent to the client after every action indicating what actions are allowed in the next round.

### Fields

| Name             | Type   | Description                                       |
| ---------------- | ------ | ------------------------------------------------- |
| availableActions | List   | The list of available actions for the next round. |
| state            | String | The name of the current state.                    |

#### Example

```
{"availableActions":["generic:spin"],"state":"MAIN","type":"game-event","name":"generic:availableActions"}
```

generic:cascadesEnd
-------------------

Message sent to the client indicating that cascades have just ended.

### Fields

| Name      | Type         | Description                                         |
| --------- | ------------ | --------------------------------------------------- |
| stateName | String       | The name of the cascades state that has just ended. |
| context   | CascadeState | The state with which the cascades ended.            |

#### Example

```
{"stateName":"MAIN_CASCADES","context":{"bet":100,"panel":{"reels":[{"symbols":["ROCKET"]},{"symbols":["GEAR"]},{"symbols":["GEAR"]},{"symbols":["ROCKET"]},{"symbols":["NITRO"]},{"symbols":["ROCKET"]}]},"winnings":[],"cascadeLevel":1,"multiplier":1},"type":"game-event","name":"generic:cascadesEnd"}
```

generic:cascadesProgress
------------------------

Message sent to the client indicating the progress of the cascades phase. Sent after each cascade spin.

### Fields

| Name      | Type         | Description                                 |
| --------- | ------------ | ------------------------------------------- |
| stateName | String       | The name of the cascades state in progress. |
| context   | CascadeState | The current state of the cascades phase.    |

#### Example

```
{"stateName":"MAIN","context":{"bet":100,"panel":{"reels":[{"symbols":["ROCKET"]},{"symbols":["GEAR"]},{"symbols":["GEAR"]},{"symbols":["ROCKET"]},{"symbols":["NITRO"]},{"symbols":["ROCKET"]}]},"winnings":[],"cascadeLevel":1,"multiplier":1},"type":"game-event","name":"generic:cascadesProgress"}
```

generic:cascadesStart
---------------------

Message sent to the client indicating that cascades have just started.

### Fields

| Name      | Type         | Description                                           |
| --------- | ------------ | ----------------------------------------------------- |
| stateName | String       | The name of the cascades state that has just started. |
| context   | CascadeState | The initial cascades state for the triggered phase.   |

#### Example

```
{"stateName":"MAIN_CASCADES","context":{"bet":100,"panel":{"reels":[{"symbols":["GEAR"]},{"symbols":["GEAR"]},{"symbols":["WILD_M2"]},{"symbols":["GEAR"]},{"symbols":["NITRO"]},{"symbols":["ROCKET"]}]},"winnings":[],"cascadeLevel":0,"multiplier":1},"type":"game-event","name":"generic:cascadesStart"}
```

generic:featureTriggeredWinnings
--------------------------------

Message sent to the client indicating that a feature has been triggered because there were certain winnings.

### Fields

| Name      | Type   | Description                                                   |
| --------- | ------ | ------------------------------------------------------------- |
| stateName | String | The name of the triggered state.                              |
| winnings  | List   | The list of winnings that caused the feature to be triggered. |

#### Example

```
{"stateName":"MAIN_CASCADES","winnings":[],"type":"game-event","name":"generic:featureTriggeredWinnings"}
```

generic:spin
------------

Message sent to the client after every spin action with the result of the spin.

### Fields

| Name      | Type  | Description                                                                                                                                     |
| --------- | ----- | ----------------------------------------------------------------------------------------------------------------------------------------------- |
| betAmount | long  | The bet amount used in the last spin action.                                                                                                    |
| panel     | Panel | The panel that have been evaluated and used to generate the winnings.                                                                           |
| basePanel | Panel | Base panel that was generated before applying any kind of modifiers, it will be identical to the field panel if there was no modifiers applied. |

#### Example

```
{"betAmount":100,"panel":{"reels":[{"symbols":["ROCKET"]},{"symbols":["GEAR"]},{"symbols":["GEAR"]},{"symbols":["NITRO"]},{"symbols":["NITRO"]},{"symbols":["GEAR"]}]},"basePanel":{"reels":[{"symbols":["ROCKET"]},{"symbols":["GEAR"]},{"symbols":["GEAR"]},{"symbols":["NITRO"]},{"symbols":["NITRO"]},{"symbols":["GEAR"]}]},"type":"game-event","name":"generic:spin"}
```

generic:symbolMovement
----------------------

Message sent to the client indicating that certain symbols have been moved from one place to another. The order in which this event is sent is important as it can happen before and after the evaluation of the panel.

### Fields

| Name          | Type   | Description                                           |
| ------------- | ------ | ----------------------------------------------------- |
| originalPanel | Panel  | The panel before the symbols are moved.               |
| newPanel      | Panel  | The resulting panel after the symbols are moved.      |
| transitions   | List   | The set of symbol movements that occurred.            |
| componentName | String | The name of the panel modifier that has been applied. |

#### Example

```
{"originalPanel":{"reels":[{"symbols":["GEAR"]},{"symbols":["GEAR"]},{"symbols":["WILD_M2"]},{"symbols":["GEAR"]},{"symbols":["NITRO"]},{"symbols":["ROCKET"]}]},"newPanel":{"reels":[{"symbols":["ROCKET"]},{"symbols":["GEAR"]},{"symbols":["GEAR"]},{"symbols":["ROCKET"]},{"symbols":["NITRO"]},{"symbols":["ROCKET"]}]},"transitions":[{"from":null,"to":{"reel":0,"row":0}},{"from":null,"to":{"reel":1,"row":0}},{"from":null,"to":{"reel":2,"row":0}},{"from":null,"to":{"reel":3,"row":0}}],"componentName":"AvalancheReelsModifier","type":"game-event","name":"generic:symbolMovement"}
```

generic:winnings
----------------

Message sent to the client when winnings have been produced.

### Fields

| Name                | Type | Description                           |
| ------------------- | ---- | ------------------------------------- |
| winnings            | List | The list of produced winnings.        |
| monetaryWinningsSum | long | The total monetary winnings produced. |

#### Example

```
{"winnings":[{"winningType":"LINE","prizeType":"MONEY","winnings":200,"mainSymbol":"GEAR","occurrences":4,"id":"1-0-3","symbols":["GEAR","GEAR","WILD_M2","GEAR"],"coords":[{"reel":0,"row":0},{"reel":1,"row":0},{"reel":2,"row":0},{"reel":3,"row":0}]}],"monetaryWinningsSum":200,"type":"game-event","name":"generic:winnings"}
```

Custom Types
============

This section types used in the above actions and events.

CascadeState
------------

The last state of the game in a given cascade state.

### Fields

| Name         | Type  | Description                                             |
| ------------ | ----- | ------------------------------------------------------- |
| bet          | long  | The last bet amount used in the cascade game.           |
| panel        | Panel | The last panel shown in the cascade game.               |
| winnings     | List  | The list of last winnings produced in the cascade game. |
| cascadeLevel | int   | The current cascade level in a given cascade game       |
| multiplier   | long  | The last prize multiplier used in the cascade game.     |

FreeSpinState
-------------

The last state of the game in a given free spin state.

### Fields

| Name                         | Type  | Description                                                                      |
| ---------------------------- | ----- | -------------------------------------------------------------------------------- |
| bet                          | long  | The last bet amount used in the free spin game.                                  |
| panel                        | Panel | The last panel shown in the free spin game.                                      |
| winnings                     | List  | The list of last winnings produced in the free spin game.                        |
| multiplier                   | long  | The last prize multiplier used in the free spin game.                            |
| freeSpinRemaining            | int   | The number of free spins to complete the free spin game.                         |
| symbolsInPanelsStopCondition | List  | The symbols to appear in given coordinates so that the free spin game completes. |

MainState
---------

The last state of the game in the MAIN state.

### Fields

| Name       | Type  | Description                                          |
| ---------- | ----- | ---------------------------------------------------- |
| bet        | long  | The last bet amount used in the MAIN game.           |
| panel      | Panel | The last panel shown in the MAIN game.               |
| winnings   | List  | The list of last winnings produced in the MAIN game. |
| multiplier | long  | The last prize multiplier used in the MAIN game.     |

PickAwardWrapper
----------------

Represents a pickable item and whether it has been picked or not.

### Fields

| Name   | Type      | Description                                                   |
| ------ | --------- | ------------------------------------------------------------- |
| award  | PickAward | The actual picked element with its information.               |
| picked | boolean   | A boolean stating whether the element has been picked or not. |
| level  | int       | The level in which this element is.                           |
| index  | int       | The index inside the level in which this element is.          |

PickerState
-----------

The last state of the game in a picker state.

### Fields

| Name                       | Type | Description                                                                                         |
| -------------------------- | ---- | --------------------------------------------------------------------------------------------------- |
| bet                        | long | The bet amount with which the picker game was reached.                                              |
| currentLevel               | int  | If the picker game has levels, the current level of the picker game.                                |
| items                      | List | The list of items picked in each level of a picker game.                                            |
| accumulatedTotalWinnings   | long | The accumulated monetary winnings at a given point of the picker game.                              |
| unusedLevelEffectsPerLevel | List | The list of level effects that have been picked per level that have not yet been used for anything. |
| picksPerformed             | int  | The total number of picks performed in a picker game.                                               |
| picksRemaining             | int  | The number of remaining picks to perform to complete a picker game.                                 |

PickAward
---------

Represents the configuration for a given item in a picker game.

### Fields

| Name              | Type              | Description                                                                    |
| ----------------- | ----------------- | ------------------------------------------------------------------------------ |
| name              | String            | The name of the item.                                                          |
| aggregationEffect | AggregationEffect | The aggregation effect the item may have. NONE, WIN_ALL or WIN_ALL_IN_LEVEL    |
| levelEffect       | LevelEffect       | THe leveling up or down effect the item may have. NONE, LEVEL_UP or LEVEL_DOWN |
| awardType         | PickAwardType     | The type of award. MONEY or MULTIPLIER.                                        |
| awardValue        | long              | The award value associated to the above awardType.                             |
