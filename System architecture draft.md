┌──────────────┐
│              │                                     ┌────────────────Host 1─────────────────┐
│ TourImporter │                              ┌──────┤  ┌─────────────────────────────────┐  │
│  Operator 1  │───┐                      ┌──▶│Slot 1│  │TourProcessor - Operator1/Tour 1 │  │
│              │   │                      │   └──────┤  └─────────────────────────────────┘  │
└──────────────┘   │                      │          │─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─│
┌──────────────┐   │                      │   ┌──────┤  ┌─────────────────────────────────┐  │
│              │   │                      ├──▶│Slot 2│  │TourProcessor - Operator2/Tour 1 │  │
│ TourImporter │   │                      │   └──────┤  └─────────────────────────────────┘  │
│  Operator 2  │───┤                      │          │─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─│
│              │   │                      │   ┌──────┤  ┌─────────────────────────────────┐  │
└──────────────┘   │                      ├──▶│Slot 3│  │TourProcessor - Operator2/Tour 3 │  │
┌──────────────┐   │   ┌──────────────┐   │   └──────┤  └─────────────────────────────────┘  │
│              │   │   │              │   │          │─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─│
│ TourImporter │   │   │              │   │   ┌──────┤  ┌─────────────────────────────────┐  │
│  Operator 3  │───┼──▶│ QueueManager │───┤   │ Slot │  │TourProcessor - Operator1/Tour 2 │  │
│              │   │   │              │   ├──▶│ ...  │  └─────────────────────────────────┘  │
└──────────────┘   │   │              │   │   └──────┤─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─│
                   │   └──────────────┘   │   ┌──────┤  ┌─────────────────────────────────┐  │
                   │                      ├──▶│Slot N│  │TourProcessor - OperatorN/Tour N │  │
                   │                      │   └──────┤  └─────────────────────────────────┘  │
 ...               │                      │          └───────────────────────────────────────┘
                   │                      │
                   │                      │
                   │                      │          ┌────────────────Host 2─────────────────┐
                   │                      │   ┌──────┤  ┌─────────────────────────────────┐  │
┌──────────────┐   │                      ├──▶│Slot 1│  │TourProcessor - Operator1/Tour 2 │  │
│              │   │                      │   └──────┤  └─────────────────────────────────┘  │
│ TourImporter │   │                      │          │─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─│
│  Operator N  │───┘                      │   ┌──────┤  ┌─────────────────────────────────┐  │
│              │                          ├──▶│Slot 2│  │TourProcessor - Operator2/Tour 3 │  │
└──────────────┘                          │   └──────┤  └─────────────────────────────────┘  │
                                          │          │─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─│
                                          │   ┌──────┤  ┌─────────────────────────────────┐  │
                                          ├──▶│Slot 3│  │TourProcessor - Operator3/Tour 4 │  │
                                          │   └──────┤  └─────────────────────────────────┘  │
                                          │          │─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─│
                                          │   ┌──────┤  ┌─────────────────────────────────┐  │
                                          │   │ Slot │  │TourProcessor - OperatorN/Tour X │  │
                                          ├──▶│ ...  │  └─────────────────────────────────┘  │
                                          │   └──────┤─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─│
                                          │   ┌──────┤  ┌─────────────────────────────────┐  │
                                          └──▶│Slot N│  │TourProcessor - OperatorN/Tour Y │  │
                                              └──────┤  └─────────────────────────────────┘  │
                                                     └───────────────────────────────────────┘