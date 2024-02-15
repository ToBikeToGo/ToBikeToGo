import React, { useState } from 'react';
import Drawer from '@mui/material/Drawer';
import IconButton from '@mui/material/IconButton';
import NotificationsIcon from '@mui/icons-material/Notifications';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import { useUserContext } from '../../hooks/UserContext.jsx';

const NotificationBar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const {
    user: { notifications = [] },
  } = useUserContext();

  const toggleDrawer = (open) => (event) => {
    if (
      event.type === 'keydown' &&
      (event.key === 'Tab' || event.key === 'Shift')
    ) {
      return;
    }
    setIsOpen(open);
  };

  return (
    <div>
      <IconButton onClick={toggleDrawer(true)}>
        <NotificationsIcon />
      </IconButton>
      <Drawer anchor="right" open={isOpen} onClose={toggleDrawer(false)}>
        <IconButton onClick={toggleDrawer(false)}>
          <ArrowBackIcon />
        </IconButton>
        {notifications.map(({ text }) => {
          return <p>{text}</p>;
        })}
      </Drawer>
    </div>
  );
};

export default NotificationBar;
