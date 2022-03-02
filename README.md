# Assumptions

1. As I understood from the document each of these tour files contain data for only one tour so there are multiple tour
   files for each operator.

# i. What problems do you identify in the current setup? Please enumerate them with a brief description of why you believe they are problems, and what risks they carry.

1. ProcessManager spawns several TourImporters while TourProcessor has only one "Worker" that further processes the
   data. Creating tour files as described in the document seems like a fairly simple / quick task as it only outputs
   json files. TourProcessor has to download all the related assets and create several image sizes for them which seems
   like a much more time-consuming task.
2. There is a potential risk for data corruption. If for some reason (communication delays?) TourProcessor won't be able
   to complete its work before next round of downloading tour files happens then the JSON file can be overwritten while
   being read by another process.
3. If one operator has some issue with their API then this issue can pollute up to hundreds of tour files kept in the
   storage which we won't be able to identify and delete quickly before they're further processed unless we stop the
   whole TourProcessor for all operators.
4. If tour operator removes certain tour from its API in the meantime between creating a tour file and further
   processing of its assets then there's a risk we'll have some failures when requesting tour's assets.
5. If we split import logic between TourImporter and TourProcessor then if we happen to have any special
   conditions/exceptions for handling some operations there's a risk we'll have to implement them in both applications
   so there will be some kind of code duplication involved.
6. File based queueing practically prevents scalability or at least makes it much less reliable.

# ii. What new architecture would you suggest that optimizes for performance, scalability, and reliability?

1. Perhaps it's worth considering spawning several sub-workers that handle start-to-end imports containing one tour
   each.
2. From the point of performance I'd consider so called delta-imports (so we only import data/tours that have changed
   since last successful import). Best would be if tour operators could provide us some kind of timestamp that would be
   called "lastUpdate". Any modification to the tour or its assets should cause this timestamp to update so that we know
   if we need to update tour's data or not. For operators that don't provide it we could still do full-import. One other
   way of doing it would be remote triggering single tour import by operator (sort of push-import) but this would also
   require an operator willing to solve it this way. Though it's an obvious win-win situation where both sides reduce
   their required daily processing power as well as network traffic.
3. From the scalability standpoint I'd suggest using Queue Manager that has network interface to allow spreading
   TourProcessors across multiple hosts.
4. Not using a file based QueueManager could also simplify queue management in terms of maintenance or cleanups after
   any failures. It would even be possible to do automated queue cleanups after n number of imports from certain
   operator fail to finish successfully.

[See system architecture draft here](System architecture draft.md)

# iii. How would you ensure your chosen architecture is working as expected?

1. Identify potential bottlenecks and then load-test them with sample data. In this case it would be:
   1. QueueManager throughput (number of connections and queries per second).
   2. Website DB storage (import results need to be written from several hosts at the same time).
2. Create solution's proof of concept and also test it.

