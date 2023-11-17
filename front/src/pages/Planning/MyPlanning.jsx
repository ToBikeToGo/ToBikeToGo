import { dateFnsLocalizer } from 'react-big-calendar';
import format from 'date-fns/format';
import parse from 'date-fns/parse';
import startOfWeek from 'date-fns/startOfWeek';
import getDay from 'date-fns/getDay';
import enUS from 'date-fns/locale/en-US';
import './../../style/planning.css';
import Typography from '@mui/material/Typography';
import { Planning } from '../../components/Planning/Planning.jsx';
import { useUserContext } from '../../hooks/UserContext.jsx';
import Avatar from '@mui/material/Avatar';
import { addDays, isWeekend, startOfDay } from 'date-fns';
import { MemberList } from '../../components/Planning/MemberList.jsx';
import { useShop } from '../../components/Planning/hooks/useShop.jsx';
import { useEffect } from 'react';

const locales = {
  'en-US': enUS,
};

const localizer = dateFnsLocalizer({
  format,
  parse,
  startOfWeek,
  getDay,
  locales,
});
// TODO api call get events for a month by user
const mockedEvents = [
  {
    id: 1,
    title: 'Vacation',
    start: new Date(2023, 9, 7, 10, 0, 0),
    end: new Date(2023, 9, 10, 10, 0, 0),
  },
];

const workScheduleByDay = [
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
];
const getWorkingHours = (dayOfWeek) => {
  const day = workScheduleByDay.find((schedule) => schedule.day === dayOfWeek);
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
  return workingHours !== null && !isWeekend(date);
};

const getWorkingDays = (start, end) => {
  const events = [];
  let current = startOfDay(start);
  while (current < end) {
    if (isWorkingDay(current)) {
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

function MyPlanning({ isUser = false }) {
  const { user } = useUserContext();
  const { members, name, activeMember, setActiveMember } = useShop();
  const events = getWorkingDays(new Date(), addDays(new Date(), 30));

  useEffect(() => {
    if (activeMember === null && members.length > 0) {
      setActiveMember(members[0]);
    }
  }, [activeMember]);

  const onMemberClick = (member) => {
    setActiveMember(member);
  };

  return activeMember ? (
    <div className={'flex flex-col w-full p-12 '}>
      <Typography variant={'h1'} className={'text-center p-12'}>
        {name}
      </Typography>
      <MemberList members={members} onMemberClick={onMemberClick} />
      <div>
        <div className="flex mx-auto p-2">
          <Avatar
            className={'m-4'}
            src={activeMember.profilePicture}
            sx={{
              width: '100px',
              height: '100px',
            }}
          />
          <Typography variant={'h2'}>
            {isUser ? 'My Planning' : activeMember.name + ' Planning'}
          </Typography>
        </div>

        <Planning events={events} />
      </div>
    </div>
  ) : (
    <div>Loading...</div>
  );
}

export { MyPlanning };
