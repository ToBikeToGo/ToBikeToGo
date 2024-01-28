import { addDays, isWeekend, startOfDay } from 'date-fns';
import { useEffect, useState } from 'react';
import { useUserContext } from '../../../hooks/UserContext.jsx';

const usePlanning = ({ fromConnectedUser = false }) => {
  const { user } = useUserContext();
  const [workdScheduleByDay, setWorkScheduleByDay] = useState([
    {
      day: 'Monday',
      start: '9:00',
      end: '18:00',
    },
    {
      day: 'Tuesday',
      start: '9:00',
      end: '18:00',
    },
    {
      day: 'Wednesday',
      start: '9:00',
      end: '18:00',
    },
    {
      day: 'Thursday',
      start: '9:00',
      end: '18:00',
    },
    {
      day: 'Friday',
      start: '9:00',
      end: '18:00',
    },
    {
      day: 'Saturday',
      start: '9:00',
      end: '18:00',
    },
    {
      day: 'Sunday',
      start: '9:00',
      end: '18:00',
    },
  ]);
  const getWorkingHours = (dayOfWeek) => {
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
  };

  const isWorkingDay = (date) => {
    const dayOfWeek = date.toLocaleString('en-US', { weekday: 'long' });
    const workingHours = getWorkingHours(dayOfWeek);

    return workingHours !== null;
  };

  const getWorkingDays = (start, end) => {
    const events = [];
    let current = startOfDay(start);
    while (current < end) {
      if (!isWorkingDay(current)) {
        console.log('getWorkingDays NOWOORK-> current', current);
      }

      if (isWorkingDay(current)) {
        console.log('getWorkingDays -> current', current);
        const dayOfWeek = current.toLocaleString('en-US', { weekday: 'long' });
        const workingHours = getWorkingHours(dayOfWeek);
        const workStart = new Date(current);
        const [startHour, startMinute] = workingHours.start
          .split(':')
          .map(Number);
        workStart.setHours(startHour, startMinute, 0);
        const workEnd = new Date(current);
        const [endHour, endMinute] = workingHours.end.split(':').map(Number);
        workEnd.setHours(endHour, endMinute, 0);
        events.push({
          start: workStart,
          end: workEnd,
          title: 'Jour de travail',
        });
      }
      current = addDays(current, 1);
    }
    return events;
  };

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

  const transformedSchedules = (schedules) => {
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
  };

  useEffect(() => {
    if (fromConnectedUser) {
      setWorkScheduleByDay(transformedSchedules(user?.schedules));
    }
  }, [fromConnectedUser, user?.schedules]);

  return {
    getWorkingDays,
    transformedSchedules,
  };
};

export { usePlanning };
