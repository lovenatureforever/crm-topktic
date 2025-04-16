import { isAdmin, isAdminOrTeamLeader } from "@/plugins/auth";

const permissionedRoutes = () => {
  const admin = isAdmin();
  const isNotAgent = isAdminOrTeamLeader();
  
  const defaultRoutes = [
    {
      title: 'Campaigns',
      to: { name: 'campaigns' },
      icon: { icon: 'tabler-plane' },
    },  
  ]
  
  const userRoute = {
    title: 'Users',
    to: { name: 'users' },
    icon: { icon: 'tabler-user' },
  };
  
  let routesByRole = [];
  
  if (admin)
    routesByRole = [{
      title: 'Categories',
      to: { name: 'categories' },
      icon: { icon: 'tabler-plane' },
    }]
  
  if (isNotAgent) 
    routesByRole = [
      userRoute,
      ...routesByRole
    ]

  return [
    ...defaultRoutes, 
    ...routesByRole 
  ]
}

export {
  permissionedRoutes
};

