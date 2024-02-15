import './../../style/planning.css';
import Typography from '@mui/material/Typography';
import { Planning } from '../../components/Planning/Planning.jsx';
import { useUserContext } from '../../hooks/UserContext.jsx';
import Avatar from '@mui/material/Avatar';
import { addDays } from 'date-fns';
import React, { useEffect, useMemo, useState } from 'react';
import { usePlanning } from '../../components/Planning/hooks/usePlanning.jsx';
import { Link, useParams } from 'react-router-dom';
import Button from '@mui/material/Button';
import { WbSunny } from '@mui/icons-material';
import { useTheme } from '@mui/material';
import { useUsers } from '../Admin/hooks/useUsers.js';

// TODO api call get events for a month by user
const mockedEvents = [
  {
    id: 1,
    title: 'Vacation',
    start: new Date(2023, 9, 7, 10, 0, 0),
    end: new Date(2023, 9, 10, 10, 0, 0),
  },
];

function MyPlanning({ isUser = true }) {
  const { user, userHasShop } = useUserContext();
  const { userId } = useParams();
  const theme = useTheme();
  const { getWorkingDays, vacations, setUser } = usePlanning({
    fromConnectedUser: true,
  });

  const { getUser, user: userFromApi, isLoading } = useUsers();

  const [events, setEvents] = useState(mockedEvents);

  useEffect(() => {
    getUser(userId);
  }, [userId]);

  useEffect(() => {
    setUser(userFromApi);
  }, [userFromApi]);

  useEffect(() => {
    if (userFromApi?.schedules) {
      const workingDays = getWorkingDays(new Date(), addDays(new Date(), 3000));
      setEvents([...workingDays, ...vacations]);
    }
  }, [getWorkingDays, user?.schedules, vacations]);

  return !isLoading ? (
    <div className={'flex flex-col w-full p-12 '}>
      {userHasShop && (
        <Typography variant={'h1'} className={'text-center p-12'}>
          Work at &nbsp;
          <span
            className={'p-3'}
            style={{
              backgroundColor: theme.palette.secondary.main,
            }}
          >
            {userFromApi?.shops?.[0]?.label}
          </span>
        </Typography>
      )}
      <div>
        <div className="flex mx-auto p-2">
          <Avatar
            className={'m-4'}
            src={user?.avatar}
            sx={{
              width: '100px',
              height: '100px',
            }}
          />
          <div class="flex flex-col justify-center items-center">
            <Typography variant={'h2'}>
              {isUser ? 'My Planning' : userFromApi?.firstname + ' Planning'}
            </Typography>
            <Link to="/ask-vacation">
              <Button variant={'outlined'} color={'black'} type="button">
                Ask for Vacation
                <WbSunny
                  sx={{
                    marginLeft: '10px',
                  }}
                />
              </Button>
            </Link>
          </div>
        </div>

        <Planning events={events} />
      </div>
    </div>
  ) : (
    <div>Loading...</div>
  );
}

export { MyPlanning };
