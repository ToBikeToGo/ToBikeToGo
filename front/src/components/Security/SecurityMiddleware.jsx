import { useRedirectIfNotAdmin } from '../../helpers/securityMiddleware.js';

export const CheckSecurityMiddleware = ({ children }) => {
  useRedirectIfNotAdmin();

  return <>{children}</>;
};
