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
import { Link, useNavigate } from 'react-router-dom';
import { InputBase, TextField } from '@mui/material';
import styled from 'styled-components';
import theme from '../../theme/theme.js';
import { Calendar } from '../Calendar/Calendar.jsx';
import { useCalendar } from '../Calendar/hooks/useCalendar.jsx';
import { useBookingContext } from '../../hooks/useBooking.jsx';
import { useUserContext } from '../../hooks/UserContext.jsx';
import { useTranslation } from '../../locales/hooks/getTranslation.js';
import { useEffect } from 'react';

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
  const [setAnchorElUser] = React.useState(null);
  const navigate = useNavigate();

  const {
    changeBookingDates,
    setSearchParams,
    searchParams,
    getAvailableBikes,
  } = useBookingContext();
  const { calendarRef, dates, isOpen, onChangeDate, handleOpen } = useCalendar({
    onChangeDateCallback: (d) => {
      changeBookingDates(d?.startDate, d?.endDate);
    },
  });
  const searchRef = React.useRef(null);
  const [showSearchOptions, setShowSearchOptions] = React.useState(false);

  const handleSearchClick = () => {
    setShowSearchOptions(true);
  };

  const handleCLickOutsideSearch = (e) => {
    if (searchRef.current && !searchRef.current.contains(e.target)) {
      setShowSearchOptions(false);
    }
  };

  const handleSearch = async () => {
    console.log(searchParams);
    navigate('/search-bikes');
    getAvailableBikes();
  };

  const onChangeInput = (e) => {
    setSearchParams((prev) => ({ ...prev, [e.target.name]: e.target.value }));
  };

  useEffect(() => {
    document.addEventListener('click', handleCLickOutsideSearch);
    return () => {
      document.removeEventListener('click', handleCLickOutsideSearch);
    };
  }, [handleCLickOutsideSearch]);

  const { getTranslation } = useTranslation();
  const [anchorEl, setAnchorEl] = React.useState(null);

  const pages = [
    {
      label: getTranslation('Navbar.rent.bike'),
      path: '/rent/bike/31',
    },
    {
      label: getTranslation('Navbar.planning'),
      path: '/my-planning',
    },
    {
      label: 'Vacations request',
      path: '/vacations-request/52',
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

  const handleMenu = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };
  const handleOpenNavMenu = (event) => {
    setAnchorElNav(event.currentTarget);
  };

  const handleCloseNavMenu = () => {
    setAnchorElNav(null);
  };

  const { user } = useUserContext();

  return (
    <AppBar
      position="static"
      sx={{
        backgroundColor: '#fff6f6',
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
              // Add these lines
              overflow: 'visible', // Make sure the logo is not cut off
              whiteSpace: 'normal', // Allow the logo to wrap to the next line if necessary
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
          <Search ref={searchRef}>
            <StyledInputBase
              placeholder="Find a bike…"
              inputProps={{ 'aria-label': 'search' }}
              onClick={handleSearchClick}
              onChange={onChangeInput}
              onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
              name={'label'}
            />
            <Box
              sx={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                position: 'relative',
                width: '100%',
                flexDirection: 'column',
              }}
            >
              <Calendar
                calendarRef={calendarRef}
                handleOpen={handleOpen}
                onChangeDate={onChangeDate}
                dates={dates}
                isOpen={isOpen}
              />
              {showSearchOptions && (
                <div
                  style={{
                    margin: '1em',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    opacity: showSearchOptions ? 1 : 0,
                    transition: 'opacity 1s ease-in-out',
                  }}
                  className="search-options"
                >
                  <TextField
                    label="Max Price"
                    type="number"
                    InputLabelProps={{
                      shrink: true,
                    }}
                    sx={{ m: 2 }}
                    name="maxPrice"
                    onChange={onChangeInput}
                    onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                  />
                  <TextField
                    label="Brand"
                    type="text"
                    InputLabelProps={{
                      shrink: true,
                    }}
                    name="brand"
                    onChange={onChangeInput}
                    onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                  />
                </div>
              )}
            </Box>

            <SearchIconWrapper></SearchIconWrapper>
          </Search>
          <Box sx={{ flexGrow: 0 }}>
            <IconButton onClick={handleMenu} sx={{ p: 0 }}>
              <Avatar alt={user.firstname} src={user.avatar} />
            </IconButton>
            <Menu
              id="menu-appbar"
              anchorEl={anchorEl}
              anchorOrigin={{
                vertical: 'top',
                horizontal: 'right',
              }}
              keepMounted
              transformOrigin={{
                vertical: 'top',
                horizontal: 'right',
              }}
              open={Boolean(anchorEl)}
              onClose={handleClose}
            >
              <MenuItem
                onClick={handleClose}
                component={Link}
                to={`/user/edit-profile/${user.id}`}
              >
                Edit Profile
              </MenuItem>
              <MenuItem onClick={handleClose} component={Link} to="/settings">
                Settings
              </MenuItem>
              <MenuItem onClick={handleClose} component={Link} to="/my-shops">
                My shops
              </MenuItem>
            </Menu>
          </Box>
        </Toolbar>
      </Container>
    </AppBar>
  );
}
export default ResponsiveAppBar;
