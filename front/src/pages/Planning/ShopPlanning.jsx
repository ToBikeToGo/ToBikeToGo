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
import { useEffect, useState } from 'react';
import { useShop, useShopContext } from '../../hooks/UseShop.jsx';
import { usePlanning } from '../../components/Planning/hooks/usePlanning.jsx';
import { useParams } from 'react-router-dom';
import { CircularProgress } from '@mui/material';

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

function ShopPlanning({}) {
  const { user } = useUserContext();
  const {
    members,
    name,
    shop,
    activeMember,
    setActiveMember,
    getShopWithMembers,
    isLoading,
  } = useShop();
  const { shopId } = useParams();
  const { getWorkingDays, vacations, setUser } = usePlanning({
    fromConnectedUser: false,
  });

  useEffect(() => {
    getShopWithMembers(shopId);
  }, [getShopWithMembers]);

  const [events, setEvents] = useState(mockedEvents);

  useEffect(() => {
    if (activeMember?.schedules) {
      const workingDays = getWorkingDays(new Date(), addDays(new Date(), 3000));
      setEvents([...workingDays, ...vacations]);
    }
  }, [getWorkingDays, activeMember?.schedules, vacations]);

  // add status to

  useEffect(() => {
    if (activeMember === null && members.length > 0) {
      setActiveMember(members[0]);
    }
  }, [activeMember]);

  const onMemberClick = (member) => {
    setActiveMember(member);
    setUser(member);
  };
  if (isLoading) {
    return <CircularProgress sx={{ m: 5 }} />;
  }
  return (
    <div className={'flex flex-col w-full p-12 '}>
      <Typography variant={'h1'} className={'text-center p-12'}>
        Work at {shop?.label}
      </Typography>
      <MemberList members={members} onMemberClick={onMemberClick} />
      <div>
        <div className="flex mx-auto p-2">
          <Avatar
            className={'m-4'}
            src={activeMember?.avatar}
            sx={{
              width: '100px',
              height: '100px',
            }}
          />
          <Typography variant={'h2'}>
            {activeMember.firstname} {activeMember.lastname} Planning
          </Typography>
        </div>

        <Planning events={events} />
      </div>
    </div>
  );
}

export { ShopPlanning };
