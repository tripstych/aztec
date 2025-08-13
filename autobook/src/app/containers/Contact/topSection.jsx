import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import CarbonBg from "../../components/assets/images/carbon_bg.jpg";

const TopSectionContainer = styled.div`
  ${tw`
    w-full
    flex
    justify-between
  `}
`;

const HeaderSection = styled.div`
  ${tw`
    w-full
    h-[300px]
    md:h-[500px] 
    flex
    flex-col
    items-center
    justify-center
    gap-2
    text-white
  `}
  background: url(${CarbonBg}) center/cover no-repeat;
  background-attachment: fixed;
  position: relative;

  &::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.65); /* Adjust the opacity (0.5) as needed */
    z-index: 1;
  }

  > * {
    position: relative;
    z-index: 2;
  }
`;

const HeaderText = styled.h1`
  ${tw`
    text-3xl 
    md:text-7xl 
    tracking-wide 
    font-extrabold 
    text-white
  `}
`;
const SubHeader = styled.h2`
  ${tw`
    text-xl 
    md:text-2xl 
    tracking-wide 
    font-bold 
    text-gray-200
  `}
`;

const TopSection = () => {
  return (
    <TopSectionContainer>
      <HeaderSection>
        <HeaderText>
          Contact <span className="text-cBlue">Us</span>
        </HeaderText>
        <SubHeader>We'd love to hear from you!</SubHeader>
      </HeaderSection>
    </TopSectionContainer>
  );
};

export default TopSection;
