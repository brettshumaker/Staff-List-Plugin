/**
 * Block dependencies
 */
import './style.scss';
import './editor.scss';

/**
 * Custom components
 */
import PostSelector from '../components/PostSelector';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
  InspectorControls
} = wp.editor;

/**
 * Register block
 */
export default registerBlockType(
    'simple-staff-list/single-staff-member',
    {
        title: __( 'Single Staff Member', 'simple-staff-list' ),
        description: __( 'Show a single staff member block.', 'simple-staff-list' ),
        category: 'common',
        icon: 'groups',
        attributes: {
            staffMember: {
                type: 'array',
                default: []
            },
            id: {
              type: 'string',
              default: ''
            }
        },
        keywords: [
            __( 'Staff', 'simple-staff-list' ),
            __( 'CTA', 'simple-staff-list' ),
            __( 'Shout Out', 'simple-staff-list' ),
        ],
        edit: props => {
          const { 
            attributes: { staffMember, id },
            className, setAttributes } = props;

          return (
            <div className={ className }>
              <InspectorControls>
                <PostSelector
                  onPostSelect={post => {
                    console.log('postSelector onPostSelect', post);
                    staffMember.push(post);
                    setAttributes({
                      staffMember: [...staffMember],
                      id: post.id
                    });
                  }}
                  inputPlaceholder='Type to search Staff Members'
                  hideInputOnLimit={true}
                  posts={staffMember ? staffMember : [] }
                  onChange={newValue => {
                    console.log('postSelector onChange', post);
                    setAttributes({
                      staffMember: [...newValue],
                      id: newValue.id
                    });
                  }}
                  postType={'staff-member'}
                  limit="1"
                />
              </InspectorControls>
              <div>
                {staffMember.map(staffMember => (
                  <p key={staffMember.id}>
                    {staffMember.title}
                  </p>
                ))}
              <p>{staffMember.length === 0 ? 'Please choose a Staff Member' : ''}</p>
              </div>
            </div>
          );
        },
        save: () => {
          return null;
        },
    },
);
