--SQL Queries Used in Project:


--MY SCHEDULE FOR TODAY:

-- Selects the start and end times, start and end dates,  and description  for events that the user has accepted for today

select date(start_dt) as start_date, time(start_dt) as start_time, time(end_dt) as end_time , date(end_dt) as end_date, description
		from eventinfo natural join invited
		where (date(end_dt) >= curdate() and date(start_dt) <= curdate())
		and pid = ? and response = 1;
		
		
		
		
--MY SCHEDULE

-- Selects the start and end times, start and end dates, and description that occur between 2 dates that are selected by the user


select date(start_dt) as start_date, time(start_dt) as start_time, time(end_dt) as end_time , date(end_dt) as end_date, description
		from eventinfo natural join invited
		where (date(end_dt) >= ?  and date(start_dt) <= ?)
		and pid = ? and response = 1;
		
		
		
		
		
--MY ORGANIZED

-- displays the amount of people who have accepted, declined, or have pending invitations for all events user has organized

select description, count(case when response = 1 then 1 end) as accepted, count(case when response = 2 then 1 end)  as declined,  count(case when response = 0 then 1 end) as pending
		from event left join invited using (eid)
		where event.pid = ? 
		group by description;
		

		
		
-- PENDING INVITATIONS

--Selects the start and end times, start and end dates, and description of events that the user has been invited to, but has not responded to yet


select eid, date(start_dt) as start_date, time(start_dt) as start_time, time(end_dt) as end_time , date(end_dt) as end_date, description
			from eventinfo natural join invited 
			where response = 0 and pid = ?;
		
		
		
		
-- ORGANIZE AN EVENT

-- inserts the data that the user has entered into the event table

insert into event (start_time, duration, description, pid) values (?, ?, ?, ?);

-- inserts the data that the user has entered into the eventdate table (done a maximum of 3 times, depending on how many dates the user has entered)

insert into eventdate (eid, edate) values (?, ?);
		
		
		
		
		
		
-- ISSUE INVITATIONS

-- lists events the user has organized
select eid, description
	 from event 
	 where pid = ?;
	 
-- selects people who have not been invited to the event the user has selected
select distinct pid, fname, lname 
	from person 
	where  pid not in (select pid from eventinfo natural join invited where eid = ?);
	
-- invites those people that the user had selected
insert into invited (pid, eid, response, visibility) values (?,?,0,0);




--FRIEND'S SCHEDULES

--selects people with whom the user is friends with

select sharer, level, fname, lname 
	   from friend_of  join person takes on sharer = pid 
	   where viewer = ?;
	
	
-- selects information about events that the friend the user selected has on a user-selected day (info about all events are selected even though the description of events
-- the user does not have permission for wont be displayed -- that will be filtered in later PHP code)

select date(start_dt) as start_date, time(start_dt) as start_time, time(end_dt) as end_time , date(end_dt) as end_date, description, visibility
	from (eventinfo natural join invited) join friend_of on pid = sharer
	where sharer = ? and  viewer = ? and (date(end_dt) >= ? and date(start_dt) <= ?) and response = 1;
		
		
-- This query was not used, because it only returns info about events the user has permission to see
select date(start_dt) as start_date, time(start_dt) as start_time, time(end_dt) as end_time , date(end_dt) as end_date, description, 
	from (eventinfo natural join invited) join friend_of on pid = sharer
	where sharer = ? and  viewer = ? and (date(end_dt) >= ? and date(start_dt) <= ?) and visibility <= ? and response = 1 
