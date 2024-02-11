import { addDays, isWeekend, startOfDay, isWithinInterval } from 'date-fns';
import { useCallback, useEffect, useMemo, useState } from 'react';
import { useUserContext } from '../../../hooks/UserContext.jsx';

const usePlanning = ({ fromConnectedUser = false }) => {
  const { user: initialUser } = useUserContext();
  const [workdScheduleByDay, setWorkScheduleByDay] = useState([]);
  const [user, setUser] = useState(initialUser);

  const formatVacations = (vacations) => {
    return vacations?.map((vacation) => ({
      id: vacation.id,
      title: vacation.description,
      start: new Date(vacation.startDate),
      end: new Date(vacation.endDate),
      status: vacation.status ? 'APPROVED' : 'PENDING',
    }));
  };

  const vacations = useMemo(() => {
    return formatVacations(user?.vacations || []);
  }, [user?.vacations]);

  const getWorkingHours = useCallback(
    (dayOfWeek) => {
      const day = workdScheduleByDay?.find(
        (schedule) => schedule.day === dayOfWeek
      );
      if (day) {
        return {
          start: day.start,
          end: day.end,
        };
      }
      return null;
    },
    [workdScheduleByDay]
  );

  const isWorkingDay = useCallback(
    (date) => {
      const dayOfWeek = date.toLocaleString('en-US', { weekday: 'long' });
      const workingHours = getWorkingHours(dayOfWeek);

      return workingHours !== null;
    },
    [getWorkingHours]
  );

  const getWorkingDays = useCallback(
    (start, end) => {
      const events = [];
      let current = startOfDay(start);
      while (current < end) {
        if (isWorkingDay(current)) {
          const dayOfWeek = current.toLocaleString('en-US', {
            weekday: 'long',
          });
          const workingHours = getWorkingHours(dayOfWeek);
          const workStart = new Date(current);
          const [startHour, startMinute] = workingHours.start
            .split(':')
            .map(Number);
          workStart.setHours(startHour, startMinute, 0);
          const workEnd = new Date(current);
          const [endHour, endMinute] = workingHours.end.split(':').map(Number);
          workEnd.setHours(endHour, endMinute, 0);

          const isVacationDay = vacations.some((vacation) =>
            isWithinInterval(current, {
              start: vacation.start,
              end: vacation.end,
            })
          );

          // If it's not a vacation day, add it to the events
          if (!isVacationDay) {
            events.push({
              start: workStart,
              end: workEnd,
              title: 'Jour de travail',
              bg: 'red',
            });
          }
        }
        current = addDays(current, 1);
      }
      return events;
    },
    [getWorkingHours, isWorkingDay, vacations]
  );

  const getDayOfWeek = (dow) => {
    const days = [
      'Sunday',
      'Monday',
      'Tuesday',
      'Wednesday',
      'Thursday',
      'Friday',
      'Saturday',
    ];
    return days[dow - 1];
  };

  const transformedSchedules = useCallback(
    (schedules) => {
      return schedules?.map((schedule) => {
        const startTime = new Date(schedule.startTime).getHours();
        const endTime = new Date(schedule.endTime).getHours();
        const day = getDayOfWeek(schedule.dow);
        return {
          day,
          start: `${startTime}:00`,
          end: `${endTime}:00`,
        };
      });
    },
    [
      user?.schedules,
      user?.schedules?.length,
      user?.schedules?.[0]?.startTime,
      user?.schedules?.[0]?.endTime,
    ]
  );

  useEffect(() => {
    setWorkScheduleByDay(transformedSchedules(user?.schedules));
  }, [user?.schedules, transformedSchedules]);

  return {
    getWorkingDays,
    transformedSchedules,
    formatVacations,
    vacations,
    setUser,
  };
};

export { usePlanning };
