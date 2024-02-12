import theme from '../../theme/theme.js';
import { Typography } from '@mui/material';
const Footer = () => {
  return (
    <div
      style={{
        backgroundColor: theme.palette.secondary.main,
      }}
      className={'flex justify-center items-center w-full h-20'}
    >
      <Typography
        variant="h2"
        component="h4"
        gutterBottom
        style={{ color: theme.palette.common.white }}
      >
        BikeToGo
      </Typography>
    </div>
  );
};

export { Footer };
