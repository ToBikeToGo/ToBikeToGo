import { useLocation, useNavigate } from 'react-router-dom';
import { useUserContext } from '../hooks/UserContext.jsx';

const ADMIN_ROUTES = ['/admin', '/admin/users', '/admin/users/:id'];

const isAdminRoute = (route) => {
  return ADMIN_ROUTES.includes(route);
};

export const useRedirectIfNotAdmin = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const { isAdmin } = useUserContext();

  if (isAdminRoute(location.pathname)) {
    if (!isAdmin) {
      navigate('/404');
    }
  }
};
