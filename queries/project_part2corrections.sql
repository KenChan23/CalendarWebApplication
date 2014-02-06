--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Efraiyim Zitron and Ken Chan
-- CS308
-- PROJECT PART #2
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

--  PROBLEM A:

--  Write SQL INSERT statements to represent the following situation.
--  (Note that each part requires several insert statements.)
--  If there are attributes whose values aren’t specified, you can use any reasonable values. 

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

--  1.
/**

  There are four users, Ann Andrews, Bob Baker, Cathy Chan, and Dan Dunn. 
  Each of them uses their initials as their pid (e.g, Ann’s pid is ‘AA’) and each has the MD5 has of their pid as their password.
  (You can call the function md5 from within the INSERT statement to compute these, e.g. INSERT INTO person VALUES (‘AA’, md5(‘AA’), ...). 
  Each person has default privacy level 1. 

**/

    INSERT INTO person VALUES ('AA' , md5('AA') , 'Ann' , 'Andrews' , 1),
							  ('BB' , md5('BB') , 'Bob' , 'Baker' , 1),
							  ('CC' , md5('CC') , 'Cathy' , 'Chan' , 1),
							  ('DD' , md5('DD') , 'Dan' , 'Dunn' , 1);

--  2.
/**

  Dan has organized an event with eid==1 starting at 1:00 p.m. with duration 2 hours on Oct 7, 2013 and on Oct 14, 2013. 
  (Make up the other attributes of this event.) 

**/

    INSERT INTO event VALUES (1 ,'13:00:00' , '02:00:00' ,  'Jeff\'s Birthday Party' , 'DD');
	
    INSERT INTO eventdate VALUES (1 , '2013-10-07'),
								 (1 , '2013-10-14');
								 
								 
--  3.
/**

  Dan and Ann have been invited to this event and have accepted the invitation.
  Ann has assigned visibility 2 to the event and Dan has assigned his default privacy level. 
  (For full credit, use a scalar subquery to find Dan’s default privacy level.) 

**/

    INSERT INTO invited VALUES ('AA' , 1 , 1 , 2),
							   ('DD' , 1 , 1 , (SELECT d_privacy FROM person WHERE pid = 'DD')); 

--  4.
/**

  Bob has organized an event with eid==2 starting at 2:00 p.m. with duration 1 hour on Oct 14, 2013. 

**/
  
    INSERT INTO event VALUES (2 , '14:00:00' , '01:00:00' , 'Accounting Seminar' , 'BB');
    INSERT INTO eventdate VALUES (2 , '2013-10-14');
 
--  5.
/**

  Ann has designated Bob and Cathy as friends at level 1 and level 3, respectively, as shown in the table above. 

**/

    INSERT INTO friend_of VALUES ('AA' , 'BB' , 1),
						         ('AA' , 'CC' , 3);

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

--  PROBLEM B:

--  Write SQL SELECT statements for each of the following.
--  (Optionally, you may create a view that has all the relevant information about events including one or more datetime attributes, and then use the view in some of the queries.)

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

--  6.
/**

  Determine whether there is a tuple in the person table with pid == ‘AA’ and passwd == md5(‘AA’). 

**/

    SELECT *
    FROM person
    WHERE pid = 'AA' AND passwd = md5('AA');

--  7.
/**

  Show start time and end time of events Ann has accepted for Oct 14, 2013 

**/

	CREATE VIEW eventinfo AS
	SELECT eid, addtime(edate,start_time) AS start_dt,
       addtime(addtime(edate,start_time),duration) AS end_dt,
       pid AS organized_by,
       description
	FROM event NATURAL JOIN eventdate;
 
 
 
    SELECT time(start_dt) as start_time, time(end_dt) end_time
    FROM eventinfo natural join invited
    WHERE pid = 'AA' AND response = 1 AND (date(start_dt) = '2013-10-14' or date(end_dt) = '2013-10-14' or '2013-10-14' between date(start_dt) and date(end_dt));

--  8.
/**

  Find all events organized by Dan along with the number of people who have accepted invitations to each of these events 

**/
  
    SELECT eid AS Dan_Events, COUNT(response)
    FROM event join invited using (eid)
    WHERE event.pid = 'DD' AND response = 1
    GROUP BY eid;
  
--  9.
/**

  Find events that overlap with the event with e_id = 2 

**/
 
--  Additional Comment: Date and Time need to overlap.

								 
	SELECT a.eid 
	FROM eventinfo as a, eventinfo as b
	where a.eid != 2 and b.eid = 2 and a.start_dt <= b.end_dt and a.end_dt >= b.start_dt;
	
	

--  10.
/**

  Using prepared statement syntax, write a query to the details about events Ann has accepted for Oct 14, 2013 that are visible to the user with pid Y. 
  (The prepared statement will have a place-holder for Y; when it is bound to BB the resulting query will return different events than when it is bound to CC.) 

**/

--  SQL QUERY:
--  SELECT event.* , edate FROM event NATURAL JOIN eventdate, invited 
--  WHERE eventdate.edate='2013-10-14' AND invited.pid = 'AA' AND event.eid = invited.eid AND 
--  invited.visibility <= (SELECT level FROM friend_of WHERE sharer = 'AA' AND viewer = 'CC' AND level >= 2);

--  SQL Version - Courtesy of dev.mysql.com
    PREPARE stmt1 FROM 'select event.* , edate 
                        from event natural join eventdate, invited 
                        where eventdate.edate='2013-10-14' and invited.pid = 'AA' and event.eid = invited.eid and 
                        invited.visibility <= (select level from friend_of where sharer = 'AA' and viewer = ? and level >= 2)';

--  JDBC Version - Courtesy of textbook
    PreparedStatement pStmt = conn.prepareStatement(
                              "select event.* , edate 
                              from event natural join eventdate, invited 
                              where eventdate.edate='2013-10-14' and invited.pid = 'AA' and event.eid = invited.eid and 
                              invited.visibility <= (select level from friend_of where sharer = 'AA' and viewer = ? and level >= 2)");





