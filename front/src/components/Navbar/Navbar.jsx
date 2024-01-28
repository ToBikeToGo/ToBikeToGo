import * as React from 'react';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import Menu from '@mui/material/Menu';
import MenuIcon from '@mui/icons-material/Menu';
import Container from '@mui/material/Container';
import Avatar from '@mui/material/Avatar';
import Button from '@mui/material/Button';
import MenuItem from '@mui/material/MenuItem';
import AdbIcon from '@mui/icons-material/Adb';
import { Link } from 'react-router-dom';
import { InputBase } from '@mui/material';
import styled from 'styled-components';
import theme from '../../theme/theme.js';
import { Calendar } from '../Calendar/Calendar.jsx';
import { useCalendar } from '../Calendar/hooks/useCalendar.jsx';
import { useBookingContext } from '../../hooks/useBooking.jsx';

const pages = [
  {
    label: 'Rent a bike',
    path: '/rent/bike/31',
  },
  {
    label: 'My planning',
    path: '/my-planning',
  },
  {
    label: 'Ask vacation',
    path: '/ask-vacation',
  },
  {
    label: 'Vacations request',
    path: '/vacations-request/31',
  },
  {
    label: 'Last booking',
    path: '/last-booking/682',
  },
  {
    label: 'Shops',
    path: '/shops',
  },
];
const Search = styled('div')(() => ({
  display: 'flex',
  position: 'relative',
  borderRadius: '10px',
  backgroundColor: `${theme.palette.common.white}`,
  '&:hover': {
    backgroundColor: 'white',
  },
  margin: '0 2% 0 10%',
  width: '60%',
  padding: '0.5em 1em',
  border: `1px solid ${theme.palette.secondary.main}`,
}));

const SearchIconWrapper = styled('div')(({ theme }) => ({
  padding: 10,
  height: '100%',
  position: 'absolute',
  pointerEvents: 'none',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
}));

const StyledInputBase = styled(InputBase)(({}) => ({
  color: 'inherit',
}));

function ResponsiveAppBar() {
  const [anchorElNav, setAnchorElNav] = React.useState(null);
  const [anchorElUser, setAnchorElUser] = React.useState(null);
  const { changeBookingDates } = useBookingContext();
  const { calendarRef, dates, isOpen, onChangeDate, handleOpen } = useCalendar({
    onChangeDateCallback: (d) => {
      changeBookingDates(d?.startDate, d?.endDate);
    },
  });
  const handleOpenNavMenu = (event) => {
    setAnchorElNav(event.currentTarget);
  };
  const handleOpenUserMenu = (event) => {
    setAnchorElUser(event.currentTarget);
  };

  const handleCloseNavMenu = () => {
    setAnchorElNav(null);
  };

  const handleCloseUserMenu = () => {
    setAnchorElUser(null);
  };

  return (
    <AppBar
      position="static"
      sx={{
        backgroundColor: theme.palette.background.default,
        boxShadow: 'none',
      }}
    >
      <Container maxWidth="xl">
        <Toolbar disableGutters>
          <Typography
            variant="h2"
            noWrap
            component="a"
            href="#app-bar-with-responsive-menu"
            sx={{
              mr: 2,
              display: { xs: 'none', md: 'flex' },
              fontWeight: 700,
              textDecoration: 'none',
            }}
          >
            BikeToGo
          </Typography>

          <Box sx={{ flexGrow: 1, display: { xs: 'flex', md: 'none' } }}>
            <IconButton
              size="large"
              aria-label="account of current user"
              aria-controls="menu-appbar"
              aria-haspopup="true"
              onClick={handleOpenNavMenu}
              color="inherit"
            >
              <MenuIcon />
            </IconButton>
            <Menu
              id="menu-appbar"
              anchorEl={anchorElNav}
              anchorOrigin={{
                vertical: 'bottom',
                horizontal: 'left',
              }}
              keepMounted
              transformOrigin={{
                vertical: 'top',
                horizontal: 'left',
              }}
              open={Boolean(anchorElNav)}
              onClose={handleCloseNavMenu}
              sx={{
                display: { xs: 'block', md: 'none' },
              }}
            >
              {pages.map(({ label }) => (
                <MenuItem key={label} onClick={handleCloseNavMenu}>
                  <Typography textAlign="center" color={'primary'}>
                    {label}
                  </Typography>
                </MenuItem>
              ))}
            </Menu>
          </Box>
          <AdbIcon sx={{ display: { xs: 'flex', md: 'none' }, mr: 1 }} />
          <Typography
            variant="h5"
            noWrap
            component="a"
            href="#app-bar-with-responsive-menu"
            sx={{
              mr: 2,
              display: { xs: 'flex', md: 'none' },
              flexGrow: 1,
              fontFamily: 'monospace',
              fontWeight: 700,
              letterSpacing: '.3rem',
              color: 'inherit',
              textDecoration: 'none',
            }}
          >
            BikeToGo
          </Typography>
          <Box sx={{ flexGrow: 1, display: { xs: 'none', md: 'flex' } }}>
            {pages.map(({ label, path }) => (
              <Button
                key={path}
                onClick={handleCloseNavMenu}
                sx={{
                  my: 2,
                  color: theme.palette.text.secondary,
                  display: 'block',
                }}
                component={Link}
                to={path}
              >
                {label}
              </Button>
            ))}
          </Box>
          <Search>
            <StyledInputBase
              placeholder="Find a bike…"
              inputProps={{ 'aria-label': 'search' }}
            />
            <Calendar
              calendarRef={calendarRef}
              handleOpen={handleOpen}
              onChangeDate={onChangeDate}
              dates={dates}
              isOpen={isOpen}
            />

            <SearchIconWrapper></SearchIconWrapper>
          </Search>
          <Box sx={{ flexGrow: 0 }}>
            <IconButton onClick={handleOpenUserMenu} sx={{ p: 0 }}>
              <Avatar alt="Remy Sharp" src="/static/images/avatar/2.jpg" />
            </IconButton>
          </Box>
        </Toolbar>
      </Container>
    </AppBar>
  );
}
export default ResponsiveAppBar;
